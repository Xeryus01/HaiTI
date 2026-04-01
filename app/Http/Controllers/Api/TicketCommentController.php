<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\Log;
use App\Models\Attachment;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'sometimes|boolean',
            // allow status change when technicians/admins comment
            'status' => 'sometimes|string|in:' . implode(',', Ticket::statuses()),
            'attachment' => 'sometimes|file|max:10240',
        ]);

        $user = $request->user();

        // User only can comment on own ticket
        if (! $user->hasAnyRole(['Admin', 'Teknisi']) && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        // Normal user cannot create internal notes
        $isInternal = (bool) ($validated['is_internal'] ?? false);
        if (! $user->hasAnyRole(['Admin', 'Teknisi'])) {
            $isInternal = false;
        }

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'is_internal' => $isInternal,
        ]);

        // handle optional attachment
        if (isset($validated['attachment'])) {
            $file = $validated['attachment'];
            $path = $file->store('attachments', 'public');
            Attachment::create([
                'ticket_id' => $ticket->id,
                'comment_id' => $comment->id,
                'uploader_id' => $user->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }

        // if status change requested
        if (isset($validated['status']) && $user->hasAnyRole(['Admin', 'Teknisi'])) {
            $ticket->status = $validated['status'];
            if (in_array($ticket->status, [Ticket::STATUS_SOLVED, Ticket::STATUS_SOLVED_WITH_NOTES], true)) {
                $ticket->resolved_at = now();
            }
            $ticket->save();

            Log::create([
                'actor_id' => $user->id,
                'entity_type' => 'TICKET',
                'entity_id' => $ticket->id,
                'action' => 'STATUS_CHANGED',
                'meta' => ['status' => $ticket->status],
            ]);
        }

        Log::create([
            'actor_id' => $user->id,
            'entity_type' => 'TICKET',
            'entity_id' => $ticket->id,
            'action' => 'COMMENTED',
            'meta' => ['comment_id' => $comment->id],
        ]);

        // notify requester and assignee via custom notification service
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

        return response()->json($comment->load('user'), 201);
    }
}
