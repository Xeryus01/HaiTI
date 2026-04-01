<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    protected WhatsAppService $whatsAppService;
    protected EmailService $emailService;

    public function __construct(WhatsAppService $whatsAppService, EmailService $emailService)
    {
        $this->whatsAppService = $whatsAppService;
        $this->emailService = $emailService;
    }

    /**
     * Create notification and send to user
     */
    public function notify(
        User $user,
        string $type,
        string $title,
        string $message,
        ?string $actionType = null,
        ?int $actionId = null,
        bool $sendWhatsApp = true,
        bool $sendEmail = true
    ): Notification {
        // Create system notification
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_type' => $actionType,
            'action_id' => $actionId,
        ]);

        // Send WhatsApp if phone number exists
        if ($sendWhatsApp && !empty($user->phone_number)) {
            $this->sendWhatsAppNotification($user, $notification);
        }

        // Send Email if email exists
        if ($sendEmail && !empty($user->email)) {
            $this->sendEmailNotification($user, $notification);
        }

        return $notification;
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification(User $user, Notification $notification): void
    {
        try {
            $response = $this->whatsAppService->send(
                $user->phone_number,
                $notification->message,
                $notification->title
            );

            if ($response['success'] ?? false) {
                $notification->update([
                    'whatsapp_sent' => true,
                    'whatsapp_status' => 'sent',
                    'whatsapp_response' => json_encode($response),
                ]);
            } else {
                $notification->update([
                    'whatsapp_status' => 'failed',
                    'whatsapp_response' => json_encode($response),
                ]);
            }
        } catch (\Exception $e) {
            $notification->update([
                'whatsapp_status' => 'failed',
                'whatsapp_response' => json_encode(['error' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Send Email notification
     */
    protected function sendEmailNotification(User $user, Notification $notification): void
    {
        try {
            $response = $this->emailService->send(
                $user->email,
                $notification->title,
                $notification->message
            );

            if ($response['success'] ?? false) {
                $notification->update([
                    'email_sent' => true,
                    'email_status' => 'sent',
                    'email_response' => json_encode($response),
                ]);
            } else {
                $notification->update([
                    'email_status' => 'failed',
                    'email_response' => json_encode($response),
                ]);
            }
        } catch (\Exception $e) {
            $notification->update([
                'email_status' => 'failed',
                'email_response' => json_encode(['error' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Create ticket notification
     */
    public function notifyTicketCreated(User $user, $ticket, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'success',
            '📋 Tiket Baru Dibuat',
            "Tiket '{$ticket->title}' dengan prioritas {$ticket->priority} telah dibuat.",
            'ticket',
            $ticket->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Create ticket status update notification
     */
    public function notifyTicketUpdated(User $user, $ticket, string $oldStatus, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'info',
            '🔄 Tiket Diperbarui',
            "Tiket '{$ticket->code}' status berubah dari {$oldStatus} menjadi {$ticket->status}.",
            'ticket',
            $ticket->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Create ticket resolved notification
     */
    public function notifyTicketResolved(User $user, $ticket, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'success',
            '✅ Tiket Diselesaikan',
            "Tiket '{$ticket->code}' telah diselesaikan.",
            'ticket',
            $ticket->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Create reservation notification
     */
    public function notifyReservationCreated(User $user, $reservation, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'info',
            '🏢 Pengajuan Zoom Baru',
            "Pengajuan Zoom '{$reservation->room_name}' pada {$reservation->start_time->format('d/m/Y H:i')} telah dibuat.",
            'reservation',
            $reservation->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Create reservation approved notification
     */
    public function notifyReservationApproved(User $user, $reservation, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'success',
            '✅ Pengajuan Zoom Ditindaklanjuti',
            "Pengajuan Zoom '{$reservation->room_name}' telah disetujui." . ($reservation->zoom_link ? " Link: {$reservation->zoom_link}" : ''),
            'reservation',
            $reservation->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Create asset notification
     */
    public function notifyAssetCreated(User $user, $asset, bool $sendWhatsApp = true, bool $sendEmail = true): Notification
    {
        return $this->notify(
            $user,
            'info',
            '📦 Aset Baru Ditambahkan',
            "Aset '{$asset->name}' (Kode: {$asset->asset_code}) telah ditambahkan ke sistem.",
            'asset',
            $asset->id,
            $sendWhatsApp,
            $sendEmail
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): void
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Mark all user notifications as read
     */
    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get user's unread notifications
     */
    public function getUnreadNotifications(User $user, int $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's all notifications
     */
    public function getNotifications(User $user, int $limit = 20)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
