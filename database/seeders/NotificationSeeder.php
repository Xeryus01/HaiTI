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
        $testUser = User::where('email', 'test@example.com')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();
        $sampleTicket = Ticket::where('code', 'TKT-2026-004')->first();
        $sampleReservation = Reservation::where('code', 'RES-2026-001')->first();

        if ($testUser) {
            Notification::updateOrCreate(
                [
                    'user_id' => $testUser->id,
                    'title' => 'Reservasi Zoom Anda Disetujui',
                ],
                [
                    'type' => 'success',
                    'message' => 'Reservasi ruang Zoom Anda untuk Rapat Koordinasi Mingguan telah disetujui.',
                    'action_type' => 'reservation',
                    'action_id' => optional($sampleReservation)->id,
                    'is_read' => false,
                    'email_sent' => true,
                ]
            );

            Notification::updateOrCreate(
                [
                    'user_id' => $testUser->id,
                    'title' => 'Status tiket diperbarui',
                ],
                [
                    'type' => 'info',
                    'message' => 'Status tiket Anda telah diperbarui oleh tim IT.',
                    'action_type' => 'ticket',
                    'action_id' => optional($sampleTicket)->id,
                    'is_read' => false,
                    'email_sent' => true,
                ]
            );
        }

        if ($adminUser) {
            Notification::updateOrCreate(
                [
                    'user_id' => $adminUser->id,
                    'title' => 'Tiket Baru Diajukan',
                ],
                [
                    'type' => 'info',
                    'message' => 'Terdapat tiket baru yang diajukan oleh user untuk ditindaklanjuti.',
                    'action_type' => 'ticket',
                    'action_id' => optional($sampleTicket)->id,
                    'is_read' => false,
                    'email_sent' => false,
                ]
            );
        }
    }
}
