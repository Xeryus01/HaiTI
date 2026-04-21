<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = User::whereHas('roles', function ($query) {
            $query->where('name', 'Teknisi');
        })->get();

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();

        $tickets = Ticket::all();

        if ($tickets->isEmpty() || $technicians->isEmpty()) {
            return;
        }

        $comments = [
            // Comments on TKT-2026-001
            [
                'ticket_code' => 'TKT-2026-001',
                'user_email' => 'fadil@example.com',
                'message' => 'Sudah menerima laporan. Saya akan cek printer di lantai 2 hari ini. Terima kasih atas informasinya.',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-001',
                'user_email' => 'admin@example.com',
                'message' => 'Kemarin sudah service printer, toner baru sudah dipasang. Coba test print sekarang.',
                'is_internal' => true,
            ],

            // Comments on TKT-2026-003
            [
                'ticket_code' => 'TKT-2026-003',
                'user_email' => 'marko@example.com',
                'message' => 'Saya sudah check backup logs. Ada error di backup script yang perlu diperbaiki. Sedang dalam proses troubleshooting.',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-003',
                'user_email' => 'marko@example.com',
                'message' => 'Sudah update backup script dan re-run backup. Data sampai 15 April sudah dibackup. Test restore dilakukan dan berhasil.',
                'is_internal' => true,
            ],
            [
                'ticket_code' => 'TKT-2026-003',
                'user_email' => 'admin@example.com',
                'message' => 'Baik, terima kasih sudah ditangani dengan cepat. Backup sudah berjalan normal kembali.',
                'is_internal' => false,
            ],

            // Comments on TKT-2026-005
            [
                'ticket_code' => 'TKT-2026-005',
                'user_email' => 'eji@example.com',
                'message' => 'Sudah dicek. Monitor dan laptop sudah dicoba dengan monitor lain dan cable HDMI lain - semuanya normal. Issue ada di port HDMI monitor yang rusak.',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-005',
                'user_email' => 'eji@example.com',
                'message' => 'Monitor akan di-repair ke vendor. Sementara itu sudah sediakan monitor replacement untuk Ahmad bisa bekerja tanpa henti.',
                'is_internal' => true,
            ],

            // Comments on TKT-2026-006
            [
                'ticket_code' => 'TKT-2026-006',
                'user_email' => 'admin@example.com',
                'message' => 'Mohon untuk check VPN configuration di komputer Anda. Download VPN client terbaru dari link di sini dan coba lagi.',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-006',
                'user_email' => 'test@example.com',
                'message' => 'Sudah update VPN client dan masih sama. Terus putus setiap 30 menit.',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-006',
                'user_email' => 'marko@example.com',
                'message' => 'Coba update firewall rules untuk IP Anda. Ini setting timeout pada server VPN yang terlalu pendek.',
                'is_internal' => true,
            ],

            // Comments on TKT-2026-009
            [
                'ticket_code' => 'TKT-2026-009',
                'user_email' => 'admin@example.com',
                'message' => 'Password SSO Anda sudah direset. Password temporary: TempPass@2026 (wajib diubah saat first login)',
                'is_internal' => false,
            ],
            [
                'ticket_code' => 'TKT-2026-009',
                'user_email' => 'test@example.com',
                'message' => 'Terimakasih! Sudah berhasil login dan password sudah diubah.',
                'is_internal' => false,
            ],
        ];

        foreach ($comments as $comment) {
            $ticket = Ticket::where('code', $comment['ticket_code'])->first();
            $user = User::where('email', $comment['user_email'])->first();

            if ($ticket && $user) {
                TicketComment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'message' => $comment['message'],
                    'is_internal' => $comment['is_internal'],
                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        }
    }
}
