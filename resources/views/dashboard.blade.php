<x-app-layout>
@php
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
@endphp

<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Ringkasan Layanan</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Halo, {{ auth()->user()->name }}. Pantau tiket, pengajuan Zoom, dan performa pelayanan dari sini.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tickets.create') }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">Ajukan Tiket</a>
                <a href="{{ route('reservations.create') }}" class="rounded-lg border border-brand-600 px-4 py-2 text-sm font-medium text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10">Ajukan Zoom</a>
            </div>
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Alur Layanan Sederhana</h2>
            <div class="mt-4 grid gap-3 md:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">1</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">User ajukan tiket termasalahan</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">2</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Admin/Teknisi tindak lanjuti keluhan</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">3</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">User ajukan ruang Zoom dan petugas menambahkan link</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                    <p class="text-xs font-semibold text-brand-600">4</p>
                    <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Data bisa diekspor dan dipantau lewat grafik</p>
                </div>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tiket Menunggu</p>
                <h3 class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ $ticketCounts['Menunggu'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tiket Diproses</p>
                <h3 class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $ticketCounts['Diproses Teknisi'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pengajuan Zoom Menunggu</p>
                <h3 class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $zoomCounts['Menunggu'] }}</h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-sm text-gray-500 dark:text-gray-400">Layanan Selesai</p>
                <h3 class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $layananSelesai }}</h3>
            </div>
        </div>

        <div class="mb-6 grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Tiket Permasalahan</h2>
                    <a href="{{ route('exports.tickets') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ekspor CSV</a>
                </div>
                <canvas id="ticketChart" height="220"></canvas>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pengajuan Zoom</h2>
                    <a href="{{ route('exports.reservations') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ekspor CSV</a>
                </div>
                <canvas id="zoomChart" height="220"></canvas>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800">
                <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Tiket Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse (\App\Models\Ticket::latest()->take(5)->get() as $ticket)
                        <div class="flex items-center justify-between gap-3 px-5 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $ticket->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->code }} • {{ $ticket->category_label }}</p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300">{{ $ticket->status_label }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada tiket.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800">
                <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Pengajuan Zoom Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse (\App\Models\Reservation::latest()->take(5)->get() as $reservation)
                        <div class="flex items-center justify-between gap-3 px-5 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reservation->room_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($reservation->start_time)->format('d/m/Y H:i') }} • {{ \Illuminate\Support\Str::limit($reservation->purpose, 50) }}</p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300">{{ $reservation->status_label }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan Zoom.</div>
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
