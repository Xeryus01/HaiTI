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
        $admin = User::where('email', 'admin@example.com')->first();
        $requesters = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        if ($requesters->isEmpty()) {
            return;
        }

        $base = now();
        $statuses = ['PENDING', 'APPROVED', 'REJECTED'];
        $roomNames = [
            'Meeting Room A',
            'Meeting Room B', 
            'Conference Room',
            'Board Room',
            'Training Room',
            'Virtual Meeting Room',
        ];
        $purposes = [
            'Rapat Koordinasi Tim',
            'Review Project Q2',
            'Client Presentation',
            'Training & Development',
            'Team Building Activity',
            'Budget Planning Meeting',
            'Strategic Planning Session',
            'Performance Review',
            'Workshop Internal',
            'Brainstorming Session',
        ];

        $zoomLinks = [
            'https://zoom.us/j/meeting-001',
            'https://zoom.us/j/meeting-002',
            'https://zoom.us/j/meeting-003',
            'https://zoom.us/j/meeting-004',
            'https://zoom.us/j/meeting-005',
        ];

        $reservationData = [
            [
                'code' => 'RES-2026-001',
                'room_name' => 'Meeting Room A',
                'purpose' => 'Rapat Koordinasi Mingguan Tim IT',
                'start_time' => $base->copy()->addDays(1)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(1)->setTime(11, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/meeting-001',
                'notes' => 'Bergabung 10 menit sebelum rapat dimulai',
            ],
            [
                'code' => 'RES-2026-002',
                'room_name' => 'Board Room',
                'purpose' => 'Review Kuartalan untuk Manajemen',
                'start_time' => $base->copy()->addDays(3)->setTime(14, 0),
                'end_time' => $base->copy()->addDays(3)->setTime(16, 0),
                'status' => 'PENDING',
                'zoom_link' => null,
                'notes' => 'Hadir langsung, offline meeting',
            ],
            [
                'code' => 'RES-2026-003',
                'room_name' => 'Training Room',
                'purpose' => 'Sesi Pelatihan Staf dan Orientasi Sistem Baru',
                'start_time' => $base->copy()->addDays(5)->setTime(9, 0),
                'end_time' => $base->copy()->addDays(5)->setTime(12, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/meeting-003',
                'notes' => 'Host membuka ruang Zoom 15 menit sebelum acara',
            ],
            [
                'code' => 'RES-2026-004',
                'room_name' => 'Meeting Room B',
                'purpose' => 'Client Presentation - PT Maju Jaya',
                'start_time' => $base->copy()->addDays(7)->setTime(13, 30),
                'end_time' => $base->copy()->addDays(7)->setTime(15, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/meeting-004',
                'notes' => 'Siapkan projector dan presentation materials',
            ],
            [
                'code' => 'RES-2026-005',
                'room_name' => 'Conference Room',
                'purpose' => 'Budget Planning Meeting FY 2027',
                'start_time' => $base->copy()->addDays(8)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(8)->setTime(12, 0),
                'status' => 'PENDING',
                'zoom_link' => null,
                'notes' => 'Buat agenda meeting sebelumnya',
            ],
            [
                'code' => 'RES-2026-006',
                'room_name' => 'Virtual Meeting Room',
                'purpose' => 'Sync Call dengan Regional Office',
                'start_time' => $base->copy()->addDays(9)->setTime(11, 0),
                'end_time' => $base->copy()->addDays(9)->setTime(12, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/meeting-005',
                'notes' => 'Meeting dengan tim di Jakarta dan Surabaya',
            ],
            [
                'code' => 'RES-2026-007',
                'room_name' => 'Training Room',
                'purpose' => 'Workshop: Microsoft Office 365 Advanced',
                'start_time' => $base->copy()->addDays(10)->setTime(9, 0),
                'end_time' => $base->copy()->addDays(10)->setTime(12, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/workshop-001',
                'notes' => 'Peserta wajib membawa laptop sendiri',
            ],
            [
                'code' => 'RES-2026-008',
                'room_name' => 'Meeting Room A',
                'purpose' => 'Team Brainstorming - Campaign Marketing Q3',
                'start_time' => $base->copy()->addDays(12)->setTime(14, 0),
                'end_time' => $base->copy()->addDays(12)->setTime(16, 0),
                'status' => 'APPROVED',
                'zoom_link' => null,
                'notes' => 'Sediakan whiteboard dan marker',
            ],
            [
                'code' => 'RES-2026-009',
                'room_name' => 'Board Room',
                'purpose' => 'Performance Review - Management Level',
                'start_time' => $base->copy()->addDays(15)->setTime(10, 0),
                'end_time' => $base->copy()->addDays(15)->setTime(16, 0),
                'status' => 'PENDING',
                'zoom_link' => null,
                'notes' => 'Confidential meeting - reserved for day',
            ],
            [
                'code' => 'RES-2026-010',
                'room_name' => 'Meeting Room B',
                'purpose' => 'Weekly Project Status Update',
                'start_time' => $base->copy()->addDays(20)->setTime(15, 0),
                'end_time' => $base->copy()->addDays(20)->setTime(16, 0),
                'status' => 'APPROVED',
                'zoom_link' => 'https://zoom.us/j/status-weekly',
                'notes' => 'Setiap Kamis jam 15:00 untuk update project status',
            ],
        ];

        foreach ($reservationData as $data) {
            $requester = $requesters->random();
            $approver = $admin;

            Reservation::updateOrCreate(
                ['code' => $data['code']],
                [
                    'room_name' => $data['room_name'],
                    'purpose' => $data['purpose'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => $data['status'],
                    'requester_id' => $requester->id,
                    'approver_id' => $data['status'] === 'APPROVED' ? $approver->id : null,
                    'zoom_link' => $data['zoom_link'],
                    'notes' => $data['notes'],
                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]
            );
        }
    }
}
