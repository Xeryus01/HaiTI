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
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->first();

        $requesters = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        if (! $admin || $requesters->isEmpty()) {
            return;
        }

        $base = now();

        $reservationData = [
            [
                'code' => 'RES-2026-001',
                'room_name' => 'Meeting Room A',
                'purpose' => 'Rapat Koordinasi Mingguan Tim IT',
                'team_name' => 'Tim Teknologi Informasi',
                'participants_count' => 12,
                'operator_needed' => true,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(1)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(1)->setTime(11, 0),
                'status' => Reservation::STATUS_APPROVED,
                'zoom_link' => 'https://zoom.us/j/meeting-001',
                'zoom_record_link' => null,
                'notes' => 'Bergabung 10 menit sebelum rapat dimulai.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-002',
                'room_name' => 'Board Room',
                'purpose' => 'Review Kuartalan untuk Manajemen',
                'team_name' => 'Divisi Keuangan',
                'participants_count' => 8,
                'operator_needed' => false,
                'breakroom_needed' => true,
                'start_time' => $base->copy()->addDays(3)->setTime(14, 0),
                'end_time' => $base->copy()->addDays(3)->setTime(16, 0),
                'status' => Reservation::STATUS_PENDING,
                'zoom_link' => null,
                'zoom_record_link' => null,
                'notes' => 'Hadir langsung, offline meeting.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-003',
                'room_name' => 'Training Room',
                'purpose' => 'Sesi Pelatihan Staf dan Orientasi Sistem Baru',
                'team_name' => 'Tim HR',
                'participants_count' => 20,
                'operator_needed' => true,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(5)->setTime(9, 0),
                'end_time' => $base->copy()->addDays(5)->setTime(12, 0),
                'status' => Reservation::STATUS_APPROVED,
                'zoom_link' => 'https://zoom.us/j/meeting-003',
                'zoom_record_link' => 'https://zoom.us/rec/record-003',
                'notes' => 'Host membuka ruang Zoom 15 menit sebelum acara.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-004',
                'room_name' => 'Meeting Room B',
                'purpose' => 'Client Presentation - PT Maju Jaya',
                'team_name' => 'Divisi Pemasaran',
                'participants_count' => 10,
                'operator_needed' => true,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(7)->setTime(13, 30),
                'end_time' => $base->copy()->addDays(7)->setTime(15, 0),
                'status' => Reservation::STATUS_APPROVED,
                'zoom_link' => 'https://zoom.us/j/meeting-004',
                'zoom_record_link' => null,
                'notes' => 'Siapkan projector dan presentation materials.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-005',
                'room_name' => 'Conference Room',
                'purpose' => 'Budget Planning Meeting FY 2027',
                'team_name' => 'Tim Perencanaan',
                'participants_count' => 16,
                'operator_needed' => false,
                'breakroom_needed' => true,
                'start_time' => $base->copy()->addDays(8)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(8)->setTime(12, 0),
                'status' => Reservation::STATUS_PENDING,
                'zoom_link' => null,
                'zoom_record_link' => null,
                'notes' => 'Buat agenda meeting sebelumnya.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-006',
                'room_name' => 'Virtual Meeting Room',
                'purpose' => 'Sync Call dengan Regional Office',
                'team_name' => 'Tim Operasional',
                'participants_count' => 14,
                'operator_needed' => true,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(9)->setTime(11, 0),
                'end_time' => $base->copy()->addDays(9)->setTime(12, 0),
                'status' => Reservation::STATUS_COMPLETED,
                'zoom_link' => 'https://zoom.us/j/meeting-005',
                'zoom_record_link' => 'https://zoom.us/rec/record-005',
                'notes' => 'Meeting dengan tim di Jakarta dan Surabaya.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-007',
                'room_name' => 'Training Room',
                'purpose' => 'Workshop: Microsoft Office 365 Advanced',
                'team_name' => 'Tim Pelatihan',
                'participants_count' => 18,
                'operator_needed' => true,
                'breakroom_needed' => true,
                'start_time' => $base->copy()->addDays(10)->setTime(9, 0),
                'end_time' => $base->copy()->addDays(10)->setTime(12, 0),
                'status' => Reservation::STATUS_WAITING_MONITORING,
                'zoom_link' => 'https://zoom.us/j/workshop-001',
                'zoom_record_link' => null,
                'notes' => 'Peserta wajib membawa laptop sendiri.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-008',
                'room_name' => 'Meeting Room A',
                'purpose' => 'Team Brainstorming - Campaign Marketing Q3',
                'team_name' => 'Divisi Marketing',
                'participants_count' => 11,
                'operator_needed' => false,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(12)->setTime(14, 0),
                'end_time' => $base->copy()->addDays(12)->setTime(16, 0),
                'status' => Reservation::STATUS_APPROVED,
                'zoom_link' => null,
                'zoom_record_link' => null,
                'notes' => 'Sediakan whiteboard dan marker.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-009',
                'room_name' => 'Board Room',
                'purpose' => 'Performance Review - Management Level',
                'team_name' => 'Tim Manajemen',
                'participants_count' => 6,
                'operator_needed' => false,
                'breakroom_needed' => true,
                'start_time' => $base->copy()->addDays(15)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(15)->setTime(16, 0),
                'status' => Reservation::STATUS_REJECTED,
                'zoom_link' => null,
                'zoom_record_link' => null,
                'notes' => 'Pengajuan ditolak karena jadwal bentrok.',
                'nota_dinas_path' => null,
            ],
            [
                'code' => 'RES-2026-010',
                'room_name' => 'Meeting Room B',
                'purpose' => 'Weekly Project Status Update',
                'team_name' => 'Tim Proyek',
                'participants_count' => 13,
                'operator_needed' => true,
                'breakroom_needed' => false,
                'start_time' => $base->copy()->addDays(20)->setTime(15, 0),
                'end_time' => $base->copy()->addDays(20)->setTime(16, 0),
                'status' => Reservation::STATUS_CANCELLED,
                'zoom_link' => null,
                'zoom_record_link' => null,
                'notes' => 'Pengajuan dibatalkan oleh pemohon.',
                'nota_dinas_path' => null,
            ],
        ];

        foreach ($reservationData as $data) {
            $requester = $requesters->random();

            Reservation::updateOrCreate(
                ['code' => $data['code']],
                [
                    'requester_id' => $requester->id,
                    'approver_id' => in_array($data['status'], [Reservation::STATUS_APPROVED, Reservation::STATUS_COMPLETED], true) ? $admin->id : null,
                    'room_name' => $data['room_name'],
                    'purpose' => $data['purpose'],
                    'team_name' => $data['team_name'],
                    'participants_count' => $data['participants_count'],
                    'operator_needed' => $data['operator_needed'],
                    'breakroom_needed' => $data['breakroom_needed'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => $data['status'],
                    'zoom_link' => $data['zoom_link'],
                    'zoom_record_link' => $data['zoom_record_link'],
                    'notes' => $data['notes'],
                    'nota_dinas_path' => $data['nota_dinas_path'],
                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]
            );
        }
    }
}
