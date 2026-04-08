<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requester = User::where('email', 'test@example.com')->first();
        $approver = User::where('email', 'teknisi@example.com')->first();

        if (! $requester) {
            return;
        }

        $base = now();
        $reservations = [
            [
                'code' => 'RES-2026-001',
                'room_name' => 'Rapat Koordinasi Mingguan',
                'purpose' => 'Koordinasi pekerjaan tim IT dan update progres ticket.',
                'start_time' => $base->copy()->addDays(1)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(1)->setTime(11, 0),
                'status' => 'APPROVED',
                'requester_id' => $requester->id,
                'approver_id' => optional($approver)->id,
                'zoom_link' => 'https://zoom.us/j/meeting-demo-001',
                'notes' => 'Silakan bergabung 10 menit sebelum rapat dimulai.',
            ],
            [
                'code' => 'RES-2026-002',
                'room_name' => 'Board Room',
                'purpose' => 'Review kuartalan untuk manajemen dan tim.',
                'start_time' => $base->copy()->addDays(3)->setTime(14, 0),
                'end_time' => $base->copy()->addDays(3)->setTime(16, 0),
                'status' => 'PENDING',
                'requester_id' => $requester->id,
            ],
            [
                'code' => 'RES-2026-003',
                'room_name' => 'Pelatihan Internal',
                'purpose' => 'Sesi pelatihan staf dan orientasi sistem baru.',
                'start_time' => $base->copy()->addDays(5)->setTime(9, 0),
                'end_time' => $base->copy()->addDays(5)->setTime(12, 0),
                'status' => 'APPROVED',
                'requester_id' => $requester->id,
                'approver_id' => optional($approver)->id,
                'zoom_link' => 'https://zoom.us/j/meeting-demo-003',
                'notes' => 'Host akan membuka ruang Zoom 15 menit sebelum acara.',
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::updateOrCreate(
                ['code' => $reservation['code']],
                $reservation
            );
        }
    }
}
