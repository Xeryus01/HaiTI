<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', 'Teknisi');
        })->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        // Get sample ticket and reservation
        $sampleTicket = Ticket::where('code', 'TKT-2026-001')->first();
        $sampleReservation = Reservation::where('code', 'RES-2026-001')->first();

        // Notifications for admin
        if ($admin) {
            // Ticket notifications
            Notification::updateOrCreate(
                [
                    'user_id' => $admin->id,
                    'title' => 'Tiket Baru Diajukan - Printer Error',
                ],
                [
                    'type' => 'info',
                    'message' => 'Terdapat tiket baru TKT-2026-001 dari Ahmad Surya tentang printer yang tidak bisa mencetak.',
                    'action_type' => 'ticket',
                    'action_id' => optional($sampleTicket)->id,
                    'is_read' => false,
                    'whatsapp_sent' => true,
                    'whatsapp_status' => 'sent',
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $admin->id,
                    'title' => 'Tiket Urgent Membutuhkan Tindakan',
                ],
                [
                    'type' => 'warning',
                    'message' => 'Tiket TKT-2026-003 dengan prioritas CRITICAL belum ditangani oleh teknisi manapun.',
                    'action_type' => 'ticket',
                    'action_id' => optional($sampleTicket)->id,
                    'is_read' => false,
                    'whatsapp_sent' => true,
                    'whatsapp_status' => 'sent',
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            // Reservation notifications
            Notification::updateOrCreate(
                [
                    'user_id' => $admin->id,
                    'title' => 'Reservasi Ruang Perlu Persetujuan',
                ],
                [
                    'type' => 'info',
                    'message' => 'Ada 2 reservasi ruang yang menunggu persetujuan Anda: RES-2026-002 dan RES-2026-005.',
                    'action_type' => 'reservation',
                    'action_id' => optional($sampleReservation)->id,
                    'is_read' => true,
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );
        }

        // Notifications for technicians
        if ($technicians->count() > 0) {
            $technician = $technicians->first();

            Notification::updateOrCreate(
                [
                    'user_id' => $technician->id,
                    'title' => 'Tiket Baru Diassign ke Anda',
                ],
                [
                    'type' => 'info',
                    'message' => 'Tiket TKT-2026-002 tentang konfigurasi email telah diassign ke Anda. Priority: MEDIUM',
                    'action_type' => 'ticket',
                    'action_id' => Ticket::where('code', 'TKT-2026-002')->first()->id ?? null,
                    'is_read' => false,
                    'whatsapp_sent' => true,
                    'whatsapp_status' => 'sent',
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $technician->id,
                    'title' => 'Reminder: Tiket Perlu Ditutup',
                ],
                [
                    'type' => 'warning',
                    'message' => 'Tiket TKT-2026-005 telah selesai 2 hari lalu. Segera tutup dan berikan update kepada requester.',
                    'action_type' => 'ticket',
                    'action_id' => Ticket::where('code', 'TKT-2026-005')->first()->id ?? null,
                    'is_read' => false,
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );
        }

        // Notifications for regular users
        if ($users->count() > 0) {
            $user = $users->first();

            Notification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => 'Reservasi Zoom Anda Disetujui',
                ],
                [
                    'type' => 'success',
                    'message' => 'Reservasi ruang RES-2026-001 untuk Rapat Koordinasi Mingguan telah disetujui oleh admin.',
                    'action_type' => 'reservation',
                    'action_id' => optional($sampleReservation)->id,
                    'is_read' => false,
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => 'Status Tiket Diperbarui',
                ],
                [
                    'type' => 'info',
                    'message' => 'Status tiket TKT-2026-004 Anda telah diperbarui menjadi SOLVED. Klik untuk melihat detail atau memberikan feedback.',
                    'action_type' => 'ticket',
                    'action_id' => Ticket::where('code', 'TKT-2026-004')->first()->id ?? null,
                    'is_read' => true,
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => 'Tiket Anda Ditolak',
                ],
                [
                    'type' => 'error',
                    'message' => 'Tiket TKT-2026-002 tidak dapat diproses karena permintaan tidak sesuai dengan kriteria. Silahkan hubungi admin untuk diskusi lebih lanjut.',
                    'action_type' => 'ticket',
                    'action_id' => Ticket::where('code', 'TKT-2026-002')->first()->id ?? null,
                    'is_read' => false,
                    'whatsapp_sent' => true,
                    'whatsapp_status' => 'sent',
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => 'Reminder: Reservasi Besok',
                ],
                [
                    'type' => 'info',
                    'message' => 'Anda memiliki reservasi ruang besok pukul 10:00 - 11:00 di Meeting Room A. Zoom link sudah tersedia.',
                    'action_type' => 'reservation',
                    'action_id' => optional($sampleReservation)->id,
                    'is_read' => true,
                    'email_sent' => true,
                    'email_status' => 'sent',
                ]
            );
        }

        // Additional random notifications for variety
        $notificationTypes = ['info', 'warning', 'success', 'error'];
        $actionTypes = ['ticket', 'reservation'];

        // Create some notifications for demonstration
        for ($i = 0; $i < 5; $i++) {
            if ($users->count() > 0) {
                $randomUser = $users->random();
                $type = $notificationTypes[array_rand($notificationTypes)];
                $actionType = $actionTypes[array_rand($actionTypes)];

                $message = match ($type) {
                    'info' => 'Informasi sistem: Maintenance server dijadwalkan hari Jumat pukul 22:00 - 23:30.',
                    'warning' => 'Peringatan: Password Anda akan expire dalam 7 hari. Segera perbarui password Anda.',
                    'success' => 'Berhasil: File backup bulanan telah selesai diproses tanpa error.',
                    'error' => 'Error: Koneksi database timeout. IT sedang menginvestigasi masalah ini.',
                    default => 'Notifikasi sistem terbaru'
                };

                Notification::create([
                    'user_id' => $randomUser->id,
                    'type' => $type,
                    'title' => ucfirst($type) . ' Notification #' . ($i + 1),
                    'message' => $message,
                    'action_type' => $actionType,
                    'action_id' => null,
                    'is_read' => rand(0, 1),
                    'email_sent' => rand(0, 1),
                    'email_status' => rand(0, 1) ? 'sent' : 'pending',
                    'created_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
