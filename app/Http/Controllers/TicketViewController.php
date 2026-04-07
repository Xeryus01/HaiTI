<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\Attachment;
use App\Models\TicketComment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Log;

class TicketViewController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $q = Ticket::query()->with(['requester', 'assignee', 'asset'])->latest();

        // allow filtering by status, priority or assignee for all users
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $q->where('priority', $request->priority);
        }
        if ($request->filled('assignee_id')) {
            $q->where('assignee_id', $request->assignee_id);
        }

        $tickets = $q->paginate(15)->withQueryString();
        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $assets = Asset::all();
        return view('tickets.create', compact('assets'));
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['code'] = Ticket::generateCode();
        $data['requester_id'] = $request->user()->id;
        $data['status'] = Ticket::STATUS_OPEN;

        $ticket = Ticket::create($data);

        Log::create([
            'actor_id' => $request->user()->id,
            'entity_type' => 'TICKET',
            'entity_id' => $ticket->id,
            'action' => 'CREATED',
            'meta' => ['code' => $ticket->code],
            'created_at' => now(),
        ]);

        // Send notification
        $this->notificationService->notifyTicketCreated($request->user(), $ticket);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created');
    }

    public function show(Request $request, Ticket $ticket)
    {
        $ticket->load('requester', 'assignee', 'asset', 'comments.user', 'comments.attachments', 'attachments.uploader');

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        $assets = Asset::all();
        return view('tickets.edit', compact('ticket','assets'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        // Only Admin can assign tickets
        if (isset($request->assignee_id) && ! $user->hasRole('Admin')) {
            abort(403, 'Hanya Admin yang dapat menugaskan petugas.');
        }

        $oldStatus = $ticket->status;
        $data = $request->validated();

        $ticket->fill($data);
        if (isset($data['status']) && in_array($data['status'], [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
            $ticket->resolved_at = now();
        }
        $ticket->save();

        // Send notification if status changed
        if ($oldStatus !== $ticket->status) {
            if (in_array($ticket->status, [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
                $this->notificationService->notifyTicketResolved($ticket->assignee ?? $ticket->requester, $ticket);
            } else {
                $this->notificationService->notifyTicketUpdated($ticket->assignee ?? $ticket->requester, $ticket, $oldStatus);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success','Ticket updated');
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Ticket deleted');
    }

    public function comment(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'message' => 'required|string',
            'status' => [
                'nullable',
                'string',
                Rule::in(array_merge([''], Ticket::statuses())),
            ],
            'attachment' => 'sometimes|file|max:10240',
        ]);

        // Create comment with only the fields that belong to TicketComment
        $commentData = [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $data['message'],
            'is_internal' => false, // Regular users can't create internal comments via web form
        ];

        $comment = TicketComment::create($commentData);

        // handle optional file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            Attachment::create([
                'ticket_id' => $ticket->id,
                'comment_id' => $comment->id,
                'uploader_id' => $request->user()->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }

        // if status was provided and user is technician/admin, update ticket
        if (isset($data['status']) && $request->user()->hasAnyRole(['Admin','Teknisi'])) {
            $oldStatus = $ticket->status;
            $ticket->status = $data['status'];
            if (in_array($ticket->status, [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
                $ticket->resolved_at = now();
            }
            $ticket->save();

            // Log status change
            Log::create([
                'actor_id' => $user->id,
                'entity_type' => 'TICKET',
                'entity_id' => $ticket->id,
                'action' => 'STATUS_CHANGED',
                'meta' => ['status' => $ticket->status],
                'created_at' => now(),
            ]);

            // Send notification if status changed
            if ($oldStatus !== $ticket->status) {
                if (in_array($ticket->status, [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
                    $this->notificationService->notifyTicketResolved($ticket->assignee ?? $ticket->requester, $ticket);
                } else {
                    $this->notificationService->notifyTicketUpdated($ticket->assignee ?? $ticket->requester, $ticket, $oldStatus);
                }
            }
        }

        // Log comment creation
        Log::create([
            'actor_id' => $user->id,
            'entity_type' => 'TICKET',
            'entity_id' => $ticket->id,
            'action' => 'COMMENTED',
            'meta' => ['comment_id' => $comment->id],
            'created_at' => now(),
        ]);

        // Send notification about new comment
        if ($ticket->requester && $ticket->requester->id !== $user->id) {
            $this->notificationService->notify(
                $ticket->requester,
                'info',
                '💬 Komentar Baru pada Tiket',
                "Komentar baru pada tiket {$ticket->code}: {$comment->message}",
                'ticket',
                $ticket->id,
                false,
                false
            );
        }

        if ($ticket->assignee && $ticket->assignee->id !== $user->id) {
            $this->notificationService->notify(
                $ticket->assignee,
                'info',
                '💬 Komentar Baru pada Tiket',
                "Komentar baru pada tiket {$ticket->code}: {$comment->message}",
                'ticket',
                $ticket->id,
                false,
                false
            );
        }

        return redirect()->route('tickets.show', $ticket)->with('success','Pesan berhasil dikirim');
    }

    public function uploadAttachment(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,csv,txt',
        ]);

        $file = $validated['file'];
        $path = $file->store('attachments', 'public');

        Attachment::create([
            'ticket_id' => $ticket->id,
            'uploader_id' => $user->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Lampiran berhasil diunggah.');
    }

    public function showAttachment(Request $request, Ticket $ticket, Attachment $attachment)
    {
        $user = $request->user();

        if ((int) $attachment->ticket_id !== (int) $ticket->id) {
            abort(404);
        }

        $diskName = Storage::disk('public')->exists($attachment->file_path) ? 'public' : 'local';
        $disk = Storage::disk($diskName);

        if (! $disk->exists($attachment->file_path)) {
            abort(404);
        }

        return $disk->response($attachment->file_path, $attachment->file_name, [
            'Content-Type' => $attachment->mime_type ?: ($disk->mimeType($attachment->file_path) ?: 'application/octet-stream'),
            'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"',
        ]);
    }
}
