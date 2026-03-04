<?php

namespace App\Notifications;

use App\Models\TicketComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCommentAdded extends Notification
{
    use Queueable;

    protected TicketComment $comment;

    public function __construct(TicketComment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("New comment on ticket {$this->comment->ticket->code}")
                    ->line($this->comment->message)
                    ->action('View Ticket', url("/tickets/{$this->comment->ticket->id}"))
                    ->line('Thank you for using our ITSM system!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'ticket_id' => $this->comment->ticket->id,
            'comment_id' => $this->comment->id,
            'message' => $this->comment->message,
            'user_id' => $this->comment->user_id,
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->comment->ticket->id,
            'comment_id' => $this->comment->id,
            'message' => $this->comment->message,
            'user_id' => $this->comment->user_id,
        ];
    }
}
