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
        $requester = User::where('email', 'test@example.com')->first();
        $assignee = User::where('email', 'teknisi@example.com')->first();
        $printerAsset = Asset::where('asset_code', 'AST-002')->first();

        if (! $requester) {
            return;
        }

        $tickets = [
            [
                'code' => 'TKT-2026-001',
                'title' => 'Printer tidak bisa mencetak',
                'description' => 'Printer di lantai 2 tidak mencetak dokumen dan menampilkan error kertas macet meskipun sudah dibersihkan.',
                'category' => 'HARDWARE_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'HIGH',
                'requester_id' => $requester->id,
                'asset_id' => optional($printerAsset)->id,
            ],
            [
                'code' => 'TKT-2026-002',
                'title' => 'Konfigurasi email client',
                'description' => 'Butuh bantuan konfigurasi email client untuk akses SSO kantor.',
                'category' => 'EMAIL_SSO',
                'status' => Ticket::STATUS_ASSIGNED_DETECT,
                'priority' => 'MEDIUM',
                'requester_id' => $requester->id,
                'assignee_id' => optional($assignee)->id,
            ],
            [
                'code' => 'TKT-2026-003',
                'title' => 'Backup server gagal',
                'description' => 'Proses backup otomatis untuk server utama gagal malam kemarin dan perlu ditinjau ulang.',
                'category' => 'NETWORK_SUPPORT',
                'status' => Ticket::STATUS_OPEN,
                'priority' => 'CRITICAL',
                'requester_id' => $requester->id,
            ],
            [
                'code' => 'TKT-2026-004',
                'title' => 'Perpanjangan lisensi Adobe',
                'description' => 'Lisensi Adobe Creative Suite perlu diperpanjang sebelum bulan depan.',
                'category' => 'SOFTWARE_SUPPORT',
                'status' => Ticket::STATUS_SOLVED,
                'priority' => 'LOW',
                'requester_id' => $requester->id,
                'assignee_id' => optional($assignee)->id,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::updateOrCreate(
                ['code' => $ticket['code']],
                $ticket
            );
        }
    }
}
