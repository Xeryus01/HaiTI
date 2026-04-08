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
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Manajemen Jadwal Piket</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola jadwal piket tim IT untuk setiap bulan</p>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-4 rounded-lg border border-green-400 bg-green-100 p-4 text-green-700 dark:border-green-500/30 dark:bg-green-500/15 dark:text-green-400">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Calendar Grid -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <?php
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $currentMonth = date('n');
        ?>

            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthNum => $monthName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $schedule = $schedules->firstWhere('month', $monthNum);
                    $isCurrentMonth = $monthNum == $currentMonth;
                ?>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow dark:border-gray-700 dark:bg-dark-800 <?php echo e($isCurrentMonth ? 'ring-2 ring-brand-500' : ''); ?>">
                    <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white sm:text-xl"><?php echo e($monthName); ?></h3>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Lantai 1:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($schedule->lantai_1 ?? '-'); ?></span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Lantai 2:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($schedule->lantai_2 ?? '-'); ?></span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">TU:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($schedule->tu ?? '-'); ?></span>
                        </div>
                    </div>

                    <a href="<?php echo e(route('piket.edit', $monthNum)); ?>" class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700 transition">
                        Edit
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
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
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views/admin/piket/index.blade.php ENDPATH**/ ?>