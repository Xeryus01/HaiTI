<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\TicketComment;
use App\Services\NotificationService;
use Illuminate\Http\Request;

use App\Http\Requests\StoreTicketRequest;
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
        $q = Ticket::query()->latest();

        // allow filtering by status, priority or assignee for technicians/admin
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $q->where('priority', $request->priority);
        }
        if ($request->filled('assignee_id')) {
            $q->where('assignee_id', $request->assignee_id);
        }

        // regular users only see their own tickets
        if (! $request->user()->hasAnyRole(['Admin', 'Teknisi'])) {
            $q->where('requester_id', $request->user()->id);
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
        $data['code'] = (new Ticket())->generateCode();
        $data['requester_id'] = $request->user()->id;
        $data['status'] = Ticket::STATUS_OPEN;

        $ticket = Ticket::create($data);

        Log::create([
            'actor_id' => $request->user()->id,
            'entity_type' => 'TICKET',
            'entity_id' => $ticket->id,
            'action' => 'CREATED',
            'meta' => ['code' => $ticket->code],
        ]);

        // Send notification
        $this->notificationService->notifyTicketCreated($request->user(), $ticket);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('requester', 'assignee', 'comments.user', 'comments.attachments', 'attachments.uploader');
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $assets = Asset::all();
        return view('tickets.edit', compact('ticket','assets'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
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
                $this->notificationService->notifyTicketUpdated($ticket->assignee ?? $ticket->requester, $ticket);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success','Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success','Ticket deleted');
    }

    public function comment(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'status' => 'sometimes|string|in:' . implode(',', Ticket::statuses()),
            'attachment' => 'sometimes|file|max:10240',
        ]);
        $data['ticket_id'] = $ticket->id;
        $data['user_id'] = $request->user()->id;
        $data['is_internal'] = false;
        $comment = TicketComment::create($data);

        // handle optional file
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments');
            \App\Models\Attachment::create([
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
            $ticket->status = $data['status'];
            if (in_array($ticket->status, [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
                $ticket->resolved_at = now();
            }
            $ticket->save();
        }

        return redirect()->route('tickets.show', $ticket)->with('success','Comment added');
    }
}
