<x-app-layout>
@php
    $user = auth()->user();
    $isAdminOrTechnician = $user->hasRole(['Admin', 'Teknisi']);

    if ($isAdminOrTechnician) {
        // Admin/Teknisi melihat semua data
        $ticketCounts = [
            'Menunggu' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Ditolak' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_REJECTED)->count(),
        ];

        $zoomCounts = [
            'Menunggu' => \App\Models\Reservation::where('status', 'PENDING')->count(),
            'Disetujui' => \App\Models\Reservation::where('status', 'APPROVED')->count(),
            'Ditolak' => \App\Models\Reservation::where('status', 'REJECTED')->count(),
            'Selesai' => \App\Models\Reservation::where('status', 'COMPLETED')->count(),
            'Dibatalkan' => \App\Models\Reservation::where('status', 'CANCELLED')->count(),
        ];

        $layananSelesai = \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('status', 'COMPLETED')->count();

        // Ambil data terbaru untuk ditampilkan
        $recentTickets = \App\Models\Ticket::latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::latest()->take(5)->get();
    } else {
        // User biasa melihat data mereka sendiri
        $ticketCounts = [
            'Menunggu' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Ditolak' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_REJECTED)->count(),
        ];

        $zoomCounts = [
            'Menunggu' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'PENDING')->count(),
            'Disetujui' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'APPROVED')->count(),
            'Ditolak' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'REJECTED')->count(),
            'Selesai' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'COMPLETED')->count(),
            'Dibatalkan' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'CANCELLED')->count(),
        ];

        $layananSelesai = \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'COMPLETED')->count();

        // Ambil data terbaru milik user
        $recentTickets = \App\Models\Ticket::where('requester_id', $user->id)->latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::where('requester_id', $user->id)->latest()->take(5)->get();
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
                <a href="{{ route('tickets.create') }}" class="rounded-lg bg-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white hover:bg-brand-700 transition-colors whitespace-nowrap">Ajukan Tiket</a>
                <a href="{{ route('reservations.create') }}" class="rounded-lg border border-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors whitespace-nowrap">Ajukan Zoom</a>
            </div>
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Alur Layanan Sederhana</h2>
            <div class="mt-4 grid gap-2 sm:gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-3 sm:p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">1</p>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white line-clamp-2">User ajukan tiket termasalahan</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 sm:p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">2</p>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white line-clamp-2">Admin/Teknisi tindak lanjuti keluhan</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 sm:p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">3</p>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white line-clamp-2">User ajukan ruang Zoom</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 sm:p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">4</p>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-900 dark:text-white line-clamp-2">Data diekspor & dipantau grafik</p>
                </div>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Tiket Menunggu</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">{{ $ticketCounts['Menunggu'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Tiket Diproses</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $ticketCounts['Diproses Teknisi'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Selesai + Catatan</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $ticketCounts['Selesai + Catatan'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Pengajuan Zoom Menunggu</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $zoomCounts['Menunggu'] }}</h3>
            </div>
        </div>

        <div class="mb-6 grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Tiket Permasalahan</h2>
                    <a href="{{ route('exports.tickets') }}" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="overflow-x-auto">
                    <canvas id="ticketChart" height="200" class="w-full"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Pengajuan Zoom</h2>
                    <a href="{{ route('exports.reservations') }}" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="overflow-x-auto">
                    <canvas id="zoomChart" height="200" class="w-full"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                        backgroundColor: ['#ef4444', '#f59e0b', '#6366f1', '#10b981', '#6b7280'],
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
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
                        backgroundColor: ['#f59e0b', '#10b981', '#ef4444', '#3b82f6', '#6b7280'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
</x-app-layout>
