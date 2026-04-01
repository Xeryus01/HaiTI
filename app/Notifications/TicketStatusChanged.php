<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class TicketStatusChanged extends Notification
{
    use Queueable;

    protected Ticket $ticket;
    protected string $oldStatus;

    public function __construct(Ticket $ticket, string $oldStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Ticket {$this->ticket->code} status updated")
                    ->line("Status changed from {$this->oldStatus} to {$this->ticket->status}.")
                    ->action('View Ticket', url("/tickets/{$this->ticket->id}"))
                    ->line('Thank you for using our ITSM system!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'ticket_id' => $this->ticket->id,
            'code' => $this->ticket->code,
            'old_status' => $this->oldStatus,
            'status' => $this->ticket->status,
        ]);
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'ticket_status',
            'ticket_id' => $this->ticket->id,
            'code' => $this->ticket->code,
            'title' => "Status tiket {$this->ticket->code} berubah",
            'message' => "Status berubah dari {$this->oldStatus} ke {$this->ticket->status}",
            'old_status' => $this->oldStatus,
            'status' => $this->ticket->status,
        ];
    }
}
