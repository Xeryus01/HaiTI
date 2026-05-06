<x-app-layout>
@php
    $user = auth()->user();
    $isAdminOrTechnician = $user->hasRole(['Admin', 'Teknisi']);

    if ($isAdminOrTechnician) {
        // Admin/Teknisi melihat semua data
        $totalTickets = \App\Models\Ticket::count();
        $totalZooms = \App\Models\Reservation::count();
        $layananSelesai = \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_COMPLETED)->count();
        $totalLayanan = $totalTickets + $totalZooms;
        $capaianPersentase = $totalLayanan > 0 ? round(($layananSelesai / $totalLayanan) * 100, 1) : 0;

        $ticketCounts = [
            'Dibuka' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Menunggu Ketersediaan Barang' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_WAITING_PARTS)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Batal' => \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_REJECTED, \App\Models\Ticket::STATUS_CANCELLED])->count(),
        ];

        $zoomCounts = [
            'Dibuka' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_PENDING)->count(),
            'Diproses Teknisi' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_APPROVED)->count(),
            'Menunggu Monitoring' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_WAITING_MONITORING)->count(),
            'Selesai' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_COMPLETED)->count(),
            'Selesai Ditolak' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_REJECTED)->count(),
            'Batal' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_CANCELLED)->count(),
        ];

        $layananBelumDilayani = \App\Models\Ticket::whereNotIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count();
        $zoomBelumDilayani = \App\Models\Reservation::where('status', '!=', \App\Models\Reservation::STATUS_COMPLETED)->count();

        // Ambil data terbaru untuk ditampilkan
        $recentTickets = \App\Models\Ticket::latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::latest()->take(5)->get();

        // Data untuk kalender zoom - semua reservasi dengan Zoom link dalam bulan ini
        $zoomEvents = \App\Models\Reservation::whereNotNull('zoom_link')
            ->where('zoom_link', '!=', '')
            ->where('start_time', '>=', now()->startOfMonth())
            ->where('end_time', '<=', now()->endOfMonth())
            ->get(['start_time', 'end_time', 'room_name', 'purpose', 'status', 'code', 'participants_count', 'operator_needed', 'breakroom_needed', 'id']);

        // Data untuk kalender piket - semua jadwal piket dalam bulan ini
        $piketEvents = \App\Models\PiketSchedule::where('week_start_date', '>=', now()->startOfMonth()->toDateString())
            ->where('week_start_date', '<=', now()->endOfMonth()->toDateString())
            ->get(['id', 'week_start_date', 'technician_1', 'technician_2', 'technician_3']);

        $zoomEventsArray = $zoomEvents->map(function($event) {
            $statusColors = [
                'PENDING' => '#fbbf24', // yellow
                'APPROVED' => '#3b82f6', // blue
                'WAITING_MONITORING' => '#f59e0b', // amber
                'COMPLETED' => '#10b981', // green
                'REJECTED' => '#ef4444', // red
                'CANCELLED' => '#6b7280', // gray
            ];

            $statusLabels = [
                'PENDING' => 'Dibuka',
                'APPROVED' => 'Diproses Teknisi',
                'WAITING_MONITORING' => 'Menunggu Monitoring',
                'COMPLETED' => 'Selesai',
                'REJECTED' => 'Ditolak',
                'CANCELLED' => 'Batal',
            ];

            return [
                'title' => $event->room_name . ' - ' . $event->code,
                'start' => $event->start_time->toISOString(),
                'end' => $event->end_time->toISOString(),
                'url' => route('reservations.edit', $event),
                'display' => 'block',
                'backgroundColor' => $statusColors[$event->status] ?? '#6b7280',
                'borderColor' => $statusColors[$event->status] ?? '#6b7280',
                'extendedProps' => [
                    'code' => $event->code,
                    'purpose' => $event->purpose,
                    'participants' => $event->participants_count,
                    'operator' => $event->operator_needed ? 'Ya' : 'Tidak',
                    'breakroom' => $event->breakroom_needed ? 'Ya' : 'Tidak',
                    'status' => $statusLabels[$event->status] ?? 'Unknown',
                    'room' => $event->room_name,
                    'start' => $event->start_time->format('d/m/Y H:i'),
                    'end' => $event->end_time->format('d/m/Y H:i'),
                ]
            ];
        })->toArray();
    } else {
        // User biasa melihat data mereka sendiri
        $totalTickets = \App\Models\Ticket::where('requester_id', $user->id)->count();
        $totalZooms = \App\Models\Reservation::where('requester_id', $user->id)->count();
        $layananSelesai = \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_COMPLETED)->count();
        $totalLayanan = $totalTickets + $totalZooms;
        $capaianPersentase = $totalLayanan > 0 ? round(($layananSelesai / $totalLayanan) * 100, 1) : 0;

        $ticketCounts = [
            'Dibuka' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Menunggu Ketersediaan Barang' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_WAITING_PARTS)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Batal' => \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_REJECTED, \App\Models\Ticket::STATUS_CANCELLED])->count(),
        ];

        $zoomCounts = [
            'Dibuka' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_PENDING)->count(),
            'Diproses Teknisi' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_APPROVED)->count(),
            'Menunggu Monitoring' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_WAITING_MONITORING)->count(),
            'Selesai' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_COMPLETED)->count(),
            'Selesai Ditolak' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_REJECTED)->count(),
            'Batal' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_CANCELLED)->count(),
        ];

        $layananBelumDilayani = \App\Models\Ticket::where('requester_id', $user->id)->whereNotIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count();
        $zoomBelumDilayani = \App\Models\Reservation::where('requester_id', $user->id)->where('status', '!=', \App\Models\Reservation::STATUS_COMPLETED)->count();

        // Ambil data terbaru milik user
        $recentTickets = \App\Models\Ticket::where('requester_id', $user->id)->latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::where('requester_id', $user->id)->latest()->take(5)->get();

        // Data untuk kalender zoom - semua reservasi user dengan Zoom link dalam bulan ini
        $zoomEvents = \App\Models\Reservation::where('requester_id', $user->id)
            ->whereNotNull('zoom_link')
            ->where('zoom_link', '!=', '')
            ->where('start_time', '>=', now()->startOfMonth())
            ->where('end_time', '<=', now()->endOfMonth())
            ->get(['start_time', 'end_time', 'room_name', 'purpose', 'status', 'code', 'participants_count', 'operator_needed', 'breakroom_needed']);

        $zoomEventsArray = $zoomEvents->map(function($event) {
            $statusColors = [
                'PENDING' => '#fbbf24', // yellow
                'APPROVED' => '#3b82f6', // blue
                'WAITING_MONITORING' => '#f59e0b', // amber
                'COMPLETED' => '#10b981', // green
                'REJECTED' => '#ef4444', // red
                'CANCELLED' => '#6b7280', // gray
            ];

            $statusLabels = [
                'PENDING' => 'Dibuka',
                'APPROVED' => 'Diproses Teknisi',
                'WAITING_MONITORING' => 'Menunggu Monitoring',
                'COMPLETED' => 'Selesai',
                'REJECTED' => 'Ditolak',
                'CANCELLED' => 'Batal',
            ];

            return [
                'title' => $event->room_name . ' - ' . $event->code,
                'start' => $event->start_time->toISOString(),
                'end' => $event->end_time->toISOString(),
                'url' => route('reservations.edit', $event),
                'display' => 'block',
                'backgroundColor' => $statusColors[$event->status] ?? '#6b7280',
                'borderColor' => $statusColors[$event->status] ?? '#6b7280',
                'extendedProps' => [
                    'code' => $event->code,
                    'purpose' => $event->purpose,
                    'participants' => $event->participants_count,
                    'operator' => $event->operator_needed ? 'Ya' : 'Tidak',
                    'breakroom' => $event->breakroom_needed ? 'Ya' : 'Tidak',
                    'status' => $statusLabels[$event->status] ?? 'Unknown',
                    'room' => $event->room_name,
                    'start' => $event->start_time->format('d/m/Y H:i'),
                    'end' => $event->end_time->format('d/m/Y H:i'),
                ]
            ];
        })->toArray();
    }
@endphp

<div class="min-h-screen">
    <div class="p-4 sm:p-5 lg:p-7.5 xl:p-9">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white truncate">Ringkasan Layanan</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Halo, {{ auth()->user()->name }}. Pantau tiket, pengajuan Zoom, dan performa.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url()->to(route('tickets.create')) }}" class="rounded-lg bg-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white hover:bg-brand-700 transition-colors whitespace-nowrap">Ajukan Tiket</a>
                <a href="{{ url()->to(route('reservations.create')) }}" class="rounded-lg border border-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors whitespace-nowrap">Ajukan Zoom</a>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Total Tiket Masalah</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalTickets }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Total Tiket Zoom</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalZooms }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Layanan Selesai</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400">{{ $layananSelesai }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Capaian (%)</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $capaianPersentase }}%</h3>
            </div>
        </div>

        <!-- Kalender Zoom -->
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Kalender Zoom</h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Hanya Zoom dengan link yang muncul. Semua event akan tampil vertikal dalam satu hari.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="flex items-center gap-1"><div class="w-3 h-3 bg-blue-500 rounded"></div> Approved</span>
                </div>
            </div>
            <div class="h-80">
                <div id="zoomCalendar" class="w-full h-full"></div>
            </div>
        </div>

        <!-- Layanan Belum Dilayani
        <div class="mb-6 grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Layanan Belum Dilayani</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $layananBelumDilayani }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Zoom Belum Dilayani</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-cyan-600 dark:text-cyan-400">{{ $zoomBelumDilayani }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Total Semua Layanan</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalLayanan }}</h3>
            </div>
        </div> -->

        <div class="mb-6 grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Tiket Permasalahan</h2>
                    <a href="{{ url()->to(route('exports.tickets')) }}" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="relative h-72">
                    <canvas id="ticketChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Pengajuan Zoom</h2>
                    <a href="{{ url()->to(route('exports.reservations')) }}" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="relative h-72">
                    <canvas id="zoomChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="border-b border-gray-200 px-4 sm:px-5 py-3 sm:py-4 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Tiket Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto dark:divide-gray-700">
                    @forelse ($recentTickets as $ticket)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $ticket->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $ticket->code }} • {{ $ticket->category_label }}</p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2 sm:px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300 whitespace-nowrap">{{ $ticket->status_label }}</span>
                        </div>
                    @empty
                        <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">Belum ada tiket.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="border-b border-gray-200 px-4 sm:px-5 py-3 sm:py-4 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Pengajuan Zoom Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto dark:divide-gray-700">
                    @forelse ($recentReservations as $reservation)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $reservation->room_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ optional($reservation->start_time)->format('d/m/Y H:i') }} • {{ \Illuminate\Support\Str::limit($reservation->purpose, 40) }}</p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2 sm:px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300 whitespace-nowrap">{{ $reservation->status_label }}</span>
                        </div>
                    @empty
                        <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan Zoom.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    #zoomCalendar .fc .fc-daygrid-event,
    #zoomCalendar .fc .fc-timegrid-event {
        padding: 0.2rem 0.35rem;
        font-size: 0.72rem;
        border-radius: 0.45rem;
        line-height: 1.1;
    }
    #zoomCalendar .fc .fc-timegrid-event {
        margin-bottom: 0.15rem;
    }
    #zoomCalendar .fc .fc-event-main-frame {
        padding: 0.15rem 0.35rem;
    }
    #zoomCalendar .fc .fc-toolbar-title {
        font-size: 0.95rem;
    }
    #zoomCalendar .fc .fc-toolbar .fc-button {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
    #zoomCalendar .fc .fc-daygrid-day-top {
        padding: 0.35rem 0.45rem;
    }
    #zoomCalendar .fc .fc-daygrid-more-link {
        font-size: 0.7rem;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Kalender Zoom
        const calendarEl = document.getElementById('zoomCalendar');
        if (calendarEl) {
            let tooltipEl = null;

            const removeTooltip = function() {
                if (tooltipEl) {
                    document.body.removeChild(tooltipEl);
                    tooltipEl = null;
                }
            };

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($zoomEventsArray),
                height: '100%',
                contentHeight: 'auto',
                eventDisplay: 'block',
                views: {
                    dayGridMonth: {
                        dayMaxEvents: false,
                        dayMaxEventRows: false
                    },
                    timeGridWeek: {
                        dayMaxEvents: false,
                        slotEventOverlap: false
                    },
                    timeGridDay: {
                        dayMaxEvents: false,
                        slotEventOverlap: false
                    }
                },
                moreLinkContent: function(arg) {
                    return `+${arg.num} lainnya`;
                },
                eventOrder: 'start,-duration',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                eventOverlap: false,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventMouseEnter: function(info) {
                    removeTooltip();
                    const props = info.event.extendedProps;
                    const tooltip = `
                        <div class="bg-gray-900 text-white p-3 rounded-lg shadow-lg max-w-[18rem]">
                            <div class="font-semibold mb-2">${props.room} • ${props.code}</div>
                            <div class="text-xs sm:text-sm space-y-1">
                                <div><strong>Tujuan:</strong> ${props.purpose}</div>
                                <div><strong>Status:</strong> ${props.status}</div>
                                <div><strong>Peserta:</strong> ${props.participants}</div>
                                <div><strong>Operator:</strong> ${props.operator}</div>
                                <div><strong>Breakroom:</strong> ${props.breakroom}</div>
                                <div><strong>Mulai:</strong> ${props.start}</div>
                                <div><strong>Selesai:</strong> ${props.end}</div>
                            </div>
                        </div>
                    `;

                    tooltipEl = document.createElement('div');
                    tooltipEl.innerHTML = tooltip;
                    tooltipEl.style.position = 'absolute';
                    tooltipEl.style.zIndex = '9999';
                    tooltipEl.style.pointerEvents = 'none';
                    document.body.appendChild(tooltipEl);

                    const rect = info.el.getBoundingClientRect();
                    tooltipEl.style.left = `${rect.left}px`;
                    tooltipEl.style.top = `${rect.top - tooltipEl.offsetHeight - 10}px`;
                },
                eventMouseLeave: function() {
                    removeTooltip();
                }
            });
            calendar.render();
        }

        const ticketCtx = document.getElementById('ticketChart');
        const zoomCtx = document.getElementById('zoomChart');

        if (ticketCtx) {
            new Chart(ticketCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($ticketCounts)),
                    datasets: [{
                        label: 'Jumlah tiket',
                        data: @json(array_values($ticketCounts)),
                        backgroundColor: ['#ef4444', '#f59e0b', '#6366f1', '#10b981', '#3b82f6', '#6b7280'],
                        borderRadius: 8,
                        barPercentage: 0.65,
                        categoryPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } }
                    }
                }
            });
        }

        if (zoomCtx) {
            new Chart(zoomCtx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($zoomCounts)),
                    datasets: [{
                        data: @json(array_values($zoomCounts)),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6b7280', '#8b5cf6'],
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } }
                    }
                }
            });
        }
    });
</script>
</x-app-layout>
