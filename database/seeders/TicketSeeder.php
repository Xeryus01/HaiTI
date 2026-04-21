<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $technicians = User::where('email', 'like', '%fadil%')->orWhere('email', 'like', '%marko%')->orWhere('email', 'like', '%eji%')->get();
        $requesters = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        if ($requesters->isEmpty()) {
            return;
        }

        $assets = Asset::all();
        $categories = ['HARDWARE_SUPPORT', 'SOFTWARE_SUPPORT', 'NETWORK_SUPPORT', 'EMAIL_SSO', 'PRINTER_SUPPORT', 'GENERAL_INQUIRY'];
        $priorities = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];
        $statuses = [Ticket::STATUS_OPEN, Ticket::STATUS_ASSIGNED_DETECT, Ticket::STATUS_SOLVED_WITH_NOTES, Ticket::STATUS_SOLVED];

        // Sample tickets
        $ticketData = [
            [
                'code' => 'TKT-2026-001',
                'title' => 'Printer tidak bisa mencetak',
                'description' => 'Printer di lantai 2 tidak mencetak dokumen dan menampilkan error kertas macet meskipun sudah dibersihkan.',
                'category' => 'PRINTER_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'HIGH',
                'asset_id' => $assets->where('asset_code', 'AST-002')->first()->id ?? null,
            ],
            [
                'code' => 'TKT-2026-002',
                'title' => 'Konfigurasi email client',
                'description' => 'Butuh bantuan konfigurasi email client untuk akses SSO kantor dengan two-factor authentication.',
                'category' => 'EMAIL_SSO',
                'status' => Ticket::STATUS_ASSIGNED_DETECT,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-003',
                'title' => 'Backup server gagal',
                'description' => 'Proses backup otomatis untuk server utama gagal malam kemarin dan perlu ditinjau ulang. Data tidak terbayak sejak tanggal 15 April.',
                'category' => 'NETWORK_SUPPORT',
                'status' => Ticket::STATUS_SOLVED,
                'priority' => 'CRITICAL',
                'asset_id' => $assets->where('asset_code', 'AST-003')->first()->id ?? null,
            ],
            [
                'code' => 'TKT-2026-004',
                'title' => 'Perpanjangan lisensi Adobe',
                'description' => 'Lisensi Adobe Creative Suite perlu diperpanjang sebelum akhir bulan April 2026.',
                'category' => 'SOFTWARE_SUPPORT',
                'status' => Ticket::STATUS_SOLVED,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-005',
                'title' => 'Monitor tidak mendeteksi sinyal HDMI',
                'description' => 'Monitor di workstation Ahmad tidak mendeteksi input HDMI dari laptop, hanya tampil "No Signal".',
                'category' => 'HARDWARE_SUPPORT',
                'status' => Ticket::STATUS_ASSIGNED_DETECT,
                'priority' => 'HIGH',
                'asset_id' => $assets->where('asset_code', 'AST-001')->first()->id ?? null,
            ],
            [
                'code' => 'TKT-2026-006',
                'title' => 'VPN tidak terkoneksi',
                'description' => 'VPN client terus disconnected setiap 30 menit dan harus di-reconnect manual. Mempengaruhi produktivitas kerja.',
                'category' => 'NETWORK_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'HIGH',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-007',
                'title' => 'Install software Adobe Illustrator',
                'description' => 'Mohon install Adobe Illustrator 2026 di komputer saya untuk keperluan design grafis kampanye marketing.',
                'category' => 'SOFTWARE_SUPPORT',
                'status' => Ticket::STATUS_SOLVED_WITH_NOTES,
                'priority' => 'LOW',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-008',
                'title' => 'WiFi sering putus di lantai 3',
                'description' => 'Koneksi WiFi di area lantai 3 sering putus terutama saat meeting, padahal sinyal terlihat kuat.',
                'category' => 'NETWORK_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-009',
                'title' => 'Password reset untuk SSO',
                'description' => 'Saya lupa password SSO dan tidak bisa login ke sistem internal. Tolong direset dengan password temporary.',
                'category' => 'EMAIL_SSO',
                'status' => Ticket::STATUS_SOLVED,
                'priority' => 'HIGH',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-010',
                'title' => 'Keyboard bermasalah - beberapa key tidak berfungsi',
                'description' => 'Tombol Q, W, dan E tidak merespon input. Keyboard masih garansi dan perlu diganti atau diperbaiki.',
                'category' => 'HARDWARE_SUPPORT',
                'status' => Ticket::STATUS_ASSIGNED_DETECT,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-011',
                'title' => 'Request akses database production',
                'description' => 'Butuh akses read-only ke database production untuk analisis data penjualan Q2.',
                'category' => 'GENERAL_INQUIRY',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
            [
                'code' => 'TKT-2026-012',
                'title' => 'Slow performance di laptop',
                'description' => 'Laptop terasa lambat saat membuka file besar atau menjalankan multiple applications. RAM usage mencapai 90%.',
                'category' => 'HARDWARE_SUPPORT',
                'status' => Ticket::STATUS_SOLVED_WITH_NOTES,
                'priority' => 'MEDIUM',
                'asset_id' => null,
            ],
        ];

        $ticketCode = 1;
        foreach ($ticketData as $data) {
            $requester = $requesters->random();
            $assignee = $technicians->count() > 0 ? $technicians->random() : null;

            Ticket::updateOrCreate(
                ['code' => $data['code']],
                [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'status' => $data['status'],
                    'priority' => $data['priority'],
                    'requester_id' => $requester->id,
                    'assignee_id' => $assignee->id ?? null,
                    'asset_id' => $data['asset_id'],
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 20)),
                ]
            );

            $ticketCode++;
        }
    }
}
