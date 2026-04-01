<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function tickets(Request $request): StreamedResponse
    {
        $user = $request->user();

        $query = Ticket::query()
            ->with(['requester', 'assignee', 'asset'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if (! $user->hasAnyRole(['Admin', 'Teknisi'])) {
            $query->where('requester_id', $user->id);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Tiket',
                'Judul',
                'Kategori',
                'Prioritas',
                'Status',
                'Pemohon',
                'Petugas',
                'Aset Terkait',
                'Tanggal Buat',
                'Tanggal Selesai',
            ]);

            foreach ($query->cursor() as $ticket) {
                fputcsv($handle, [
                    $ticket->code,
                    $ticket->title,
                    $ticket->category_label,
                    $ticket->priority_label,
                    $ticket->status_label,
                    optional($ticket->requester)->name,
                    optional($ticket->assignee)->name,
                    optional($ticket->asset)->name,
                    optional($ticket->created_at)?->format('d/m/Y H:i'),
                    optional($ticket->resolved_at)?->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, 'data-tiket-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function reservations(Request $request): StreamedResponse
    {
        $user = $request->user();

        $query = Reservation::query()
            ->with(['requester', 'approver'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if (! $user->hasAnyRole(['Admin', 'Teknisi'])) {
            $query->where('requester_id', $user->id);
        }

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Pengajuan',
                'Nama Kegiatan / Ruang',
                'Keperluan',
                'Waktu Mulai',
                'Waktu Selesai',
                'Status',
                'Link Zoom',
                'Catatan Tindak Lanjut',
                'Pemohon',
                'Ditangani Oleh',
            ]);

            foreach ($query->cursor() as $reservation) {
                fputcsv($handle, [
                    $reservation->code,
                    $reservation->room_name,
                    $reservation->purpose,
                    optional($reservation->start_time)?->format('d/m/Y H:i'),
                    optional($reservation->end_time)?->format('d/m/Y H:i'),
                    $reservation->status_label,
                    $reservation->zoom_link,
                    $reservation->notes,
                    optional($reservation->requester)->name,
                    optional($reservation->approver)->name,
                ]);
            }

            fclose($handle);
        }, 'data-pengajuan-zoom-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
