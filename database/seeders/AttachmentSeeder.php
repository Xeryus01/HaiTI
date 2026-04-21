<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $tickets = Ticket::all();
        $comments = TicketComment::all();

        if ($users->isEmpty() || $tickets->isEmpty()) {
            return;
        }

        // Ticket attachments
        $ticketAttachments = [
            [
                'ticket_code' => 'TKT-2026-001',
                'file_name' => 'printer_error_screenshot.png',
                'file_path' => 'attachments/tickets/2026/04/printer_error_screenshot.png',
                'mime_type' => 'image/png',
                'size_bytes' => 524288,
            ],
            [
                'ticket_code' => 'TKT-2026-001',
                'file_name' => 'printer_manual.pdf',
                'file_path' => 'attachments/tickets/2026/04/HP_M404n_manual.pdf',
                'mime_type' => 'application/pdf',
                'size_bytes' => 2097152,
            ],
            [
                'ticket_code' => 'TKT-2026-003',
                'file_name' => 'backup_log_2026_04_15.txt',
                'file_path' => 'attachments/tickets/2026/04/backup_log_2026_04_15.txt',
                'mime_type' => 'text/plain',
                'size_bytes' => 102400,
            ],
            [
                'ticket_code' => 'TKT-2026-005',
                'file_name' => 'monitor_test_result.xlsx',
                'file_path' => 'attachments/tickets/2026/04/monitor_test_result.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'size_bytes' => 307200,
            ],
            [
                'ticket_code' => 'TKT-2026-006',
                'file_name' => 'vpn_client_config.zip',
                'file_path' => 'attachments/tickets/2026/04/vpn_client_config.zip',
                'mime_type' => 'application/zip',
                'size_bytes' => 1048576,
            ],
            [
                'ticket_code' => 'TKT-2026-007',
                'file_name' => 'adobe_illustrator_license.txt',
                'file_path' => 'attachments/tickets/2026/04/adobe_illustrator_license.txt',
                'mime_type' => 'text/plain',
                'size_bytes' => 51200,
            ],
            [
                'ticket_code' => 'TKT-2026-011',
                'file_name' => 'data_analysis_request.docx',
                'file_path' => 'attachments/tickets/2026/04/data_analysis_request.docx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'size_bytes' => 614400,
            ],
        ];

        foreach ($ticketAttachments as $attachment) {
            $ticket = Ticket::where('code', $attachment['ticket_code'])->first();
            $uploader = $users->random();

            if ($ticket) {
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'comment_id' => null,
                    'uploader_id' => $uploader->id,
                    'file_path' => $attachment['file_path'],
                    'file_name' => $attachment['file_name'],
                    'mime_type' => $attachment['mime_type'],
                    'size_bytes' => $attachment['size_bytes'],
                    'created_at' => now()->subDays(rand(1, 20)),
                    'updated_at' => now()->subDays(rand(0, 15)),
                ]);
            }
        }

        // Comment attachments
        if ($comments->count() > 0) {
            $sampleComments = $comments->take(3);

            foreach ($sampleComments as $comment) {
                Attachment::create([
                    'ticket_id' => null,
                    'comment_id' => $comment->id,
                    'uploader_id' => $comment->user_id,
                    'file_path' => 'attachments/comments/2026/04/solution_' . uniqid() . '.pdf',
                    'file_name' => 'solution_document_' . $comment->id . '.pdf',
                    'mime_type' => 'application/pdf',
                    'size_bytes' => rand(100000, 1000000),
                    'created_at' => $comment->created_at->addHours(rand(1, 8)),
                    'updated_at' => $comment->created_at->addHours(rand(1, 8)),
                ]);
            }
        }
    }
}
