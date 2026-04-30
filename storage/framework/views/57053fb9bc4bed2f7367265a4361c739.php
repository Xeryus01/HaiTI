<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php
    $user = auth()->user();
    $isAdminOrTechnician = $user->hasRole(['Admin', 'Teknisi']);

    if ($isAdminOrTechnician) {
        // Admin/Teknisi melihat semua data
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

        $layananSelesai = \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('status', 'COMPLETED')->count();

        // Ambil data terbaru untuk ditampilkan
        $recentTickets = \App\Models\Ticket::latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::latest()->take(5)->get();
    } else {
        // User biasa melihat data mereka sendiri
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

        $layananSelesai = \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'COMPLETED')->count();

        // Ambil data terbaru milik user
        $recentTickets = \App\Models\Ticket::where('requester_id', $user->id)->latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::where('requester_id', $user->id)->latest()->take(5)->get();
    }
?>

<div class="min-h-screen">
    <div class="p-4 sm:p-5 lg:p-7.5 xl:p-9">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white truncate">Ringkasan Layanan</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Halo, <?php echo e(auth()->user()->name); ?>. Pantau tiket, pengajuan Zoom, dan performa.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(url()->to(route('tickets.create'))); ?>" class="rounded-lg bg-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white hover:bg-brand-700 transition-colors whitespace-nowrap">Ajukan Tiket</a>
                <a href="<?php echo e(url()->to(route('reservations.create'))); ?>" class="rounded-lg border border-brand-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors whitespace-nowrap">Ajukan Zoom</a>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Tiket Dibuka</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400"><?php echo e($ticketCounts['Dibuka']); ?></h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Tiket Diproses Teknisi</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400"><?php echo e($ticketCounts['Diproses Teknisi']); ?></h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Tiket Menunggu Barang</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400"><?php echo e($ticketCounts['Menunggu Ketersediaan Barang']); ?></h3>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-5 dark:border-gray-700 dark:bg-dark-800">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">Pengajuan Zoom Dibuka</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400"><?php echo e($zoomCounts['Dibuka']); ?></h3>
            </div>
        </div>

        <div class="mb-6 grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Tiket Permasalahan</h2>
                    <a href="<?php echo e(url()->to(route('exports.tickets'))); ?>" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="relative h-72">
                    <canvas id="ticketChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-5 dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Grafik Pengajuan Zoom</h2>
                    <a href="<?php echo e(url()->to(route('exports.reservations'))); ?>" class="text-xs sm:text-sm font-medium text-brand-600 hover:text-brand-700 whitespace-nowrap">Ekspor CSV</a>
                </div>
                <div class="relative h-72">
                    <canvas id="zoomChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="border-b border-gray-200 px-4 sm:px-5 py-3 sm:py-4 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Tiket Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $recentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate"><?php echo e($ticket->title); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo e($ticket->code); ?> • <?php echo e($ticket->category_label); ?></p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2 sm:px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300 whitespace-nowrap"><?php echo e($ticket->status_label); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">Belum ada tiket.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
                <div class="border-b border-gray-200 px-4 sm:px-5 py-3 sm:py-4 dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">Pengajuan Zoom Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $recentReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate"><?php echo e($reservation->room_name); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo e(optional($reservation->start_time)->format('d/m/Y H:i')); ?> • <?php echo e(\Illuminate\Support\Str::limit($reservation->purpose, 40)); ?></p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2 sm:px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/5 dark:text-gray-300 whitespace-nowrap"><?php echo e($reservation->status_label); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-4 sm:px-5 py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan Zoom.</div>
                    <?php endif; ?>
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
                    labels: <?php echo json_encode(array_keys($ticketCounts), 15, 512) ?>,
                    datasets: [{
                        label: 'Jumlah tiket',
                        data: <?php echo json_encode(array_values($ticketCounts), 15, 512) ?>,
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
                    labels: <?php echo json_encode(array_keys($zoomCounts), 15, 512) ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($zoomCounts), 15, 512) ?>,
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\BPS 1900\Documents\timcare\resources\views/dashboard.blade.php ENDPATH**/ ?>