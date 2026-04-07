<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send email notification
     */
    public function send(string $email, string $subject, string $message, ?string $actionUrl = null, ?string $actionText = null): array
    {
        try {
            if (!$email) {
                return [
                    'success' => false,
                    'status' => 'failed',
                    'error' => 'Email address is required',
                ];
            }

            // Send email using Laravel's Mail facade
            Mail::raw($message, function ($mail) use ($email, $subject, $actionUrl, $actionText) {
                $mail->to($email)
                     ->subject($subject);

                // If action URL is provided, add it to the message
                if ($actionUrl && $actionText) {
                    $message = $mail->getBody();
                    $message .= "\n\n" . $actionText . ": " . $actionUrl;
                    $mail->setBody($message);
                }
            });

            Log::info('Email sent successfully', [
                'email' => $email,
                'subject' => $subject,
            ]);

            return [
                'success' => true,
                'status' => 'sent',
                'email_response' => 'Email sent successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Email service exception', [
                'email' => $email,
                'subject' => $subject,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification with template
     */
    public function sendNotification(string $email, string $type, array $data): array
    {
        $message = $this->formatNotificationMessage($type, $data);
        $subject = $this->getNotificationSubject($type);
        $actionUrl = $data['action_url'] ?? null;
        $actionText = $data['action_text'] ?? null;

        return $this->send($email, $subject, $message, $actionUrl, $actionText);
    }

    /**
     * Format notification message berdasarkan type
     */
    private function formatNotificationMessage(string $type, array $data): string
    {
        return match ($type) {
            'ticket_created' => "Tiket baru telah dibuat:\n\n" .
                "Kode: {$data['code']}\n" .
                "Judul: {$data['title']}\n" .
                "Kategori: {$data['category']}\n\n" .
                "Deskripsi: {$data['description']}\n\n" .
                "Silakan login ke sistem HaiTI untuk melihat detail tiket.",

            'ticket_updated' => "Tiket telah diperbarui:\n\n" .
                "Kode: {$data['code']}\n" .
                "Status: {$data['status']}\n" .
                "Perubahan: {$data['change_description']}\n\n" .
                "Silakan login ke sistem HaiTI untuk melihat detail perubahan.",

            'ticket_resolved' => "Tiket telah diselesaikan:\n\n" .
                "Kode: {$data['code']}\n" .
                "Judul: {$data['title']}\n\n" .
                "Terima kasih telah menggunakan sistem HaiTI.",

            'reservation_created' => "Reservasi ruangan baru telah dibuat:\n\n" .
                "Ruangan: {$data['room_name']}\n" .
                "Tanggal: {$data['date']}\n" .
                "Waktu: {$data['time']}\n" .
                "Tujuan: {$data['purpose']}\n\n" .
                "Silakan login ke sistem HaiTI untuk melihat detail reservasi.",

            'reservation_approved' => "Reservasi ruangan telah disetujui:\n\n" .
                "Ruangan: {$data['room_name']}\n" .
                "Tanggal: {$data['date']}\n\n" .
                "Reservasi Anda telah disetujui. Silakan login ke sistem HaiTI untuk detail lebih lanjut.",

            'asset_created' => "Aset baru telah ditambahkan:\n\n" .
                "Kode: {$data['asset_code']}\n" .
                "Nama: {$data['name']}\n" .
                "Tipe: {$data['type']}\n\n" .
                "Silakan login ke sistem HaiTI untuk melihat detail aset.",

            default => "Notifikasi baru: " . json_encode($data),
        };
    }

    /**
     * Get notification subject
     */
    private function getNotificationSubject(string $type): string
    {
        return match ($type) {
            'ticket_created' => '📋 Tiket Baru - HaiTI Helpdesk',
            'ticket_updated' => '🔄 Tiket Diperbarui - HaiTI Helpdesk',
            'ticket_resolved' => '✅ Tiket Diselesaikan - HaiTI Helpdesk',
            'reservation_created' => '🏢 Reservasi Ruangan Baru - HaiTI Helpdesk',
            'reservation_approved' => '✅ Reservasi Disetujui - HaiTI Helpdesk',
            'asset_created' => '📦 Aset Baru - HaiTI Helpdesk',
            default => '📬 Notifikasi - HaiTI Helpdesk',
        };
    }
}