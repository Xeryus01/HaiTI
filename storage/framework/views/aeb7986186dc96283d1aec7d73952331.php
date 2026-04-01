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
        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl"><?php echo e($reservation->room_name); ?></h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"><?php echo e($reservation->code); ?> • <?php echo e($reservation->status_label); ?></p>
            </div>
            <div class="flex gap-2">
                <a href="<?php echo e(route('reservations.index')); ?>" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Kembali</a>
                <?php if(auth()->user()->hasAnyRole(['Admin', 'Teknisi']) || auth()->id() === $reservation->requester_id): ?>
                    <a href="<?php echo e(route('reservations.edit', $reservation)); ?>" class="rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700">Ubah</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Detail Pengajuan</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($reservation->purpose); ?></p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu mulai</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white"><?php echo e($reservation->start_time->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu selesai</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white"><?php echo e($reservation->end_time->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Nota Dinas</h2>
                    <?php if($reservation->nota_dinas_path): ?>
                        <div class="space-y-4">
                            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Nota Dinas Pengajuan</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">File PDF yang diunggah saat pengajuan</p>
                                    </div>
                                    <a href="<?php echo e(route('reservations.nota-dinas', $reservation)); ?>" target="_blank" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Buka PDF</a>
                                </div>

                                <iframe src="<?php echo e(route('reservations.nota-dinas', $reservation)); ?>" title="Nota Dinas" class="h-96 w-full rounded-lg border border-gray-200 dark:border-gray-700"></iframe>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nota dinas belum diunggah.</p>
                    <?php endif; ?>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Hasil Follow Up</h2>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Link Zoom</p>
                            <?php if($reservation->zoom_link): ?>
                                <a href="<?php echo e($reservation->zoom_link); ?>" target="_blank" class="font-medium text-brand-600 hover:underline"><?php echo e($reservation->zoom_link); ?></a>
                            <?php else: ?>
                                <p class="text-gray-700 dark:text-gray-300">Belum ditambahkan.</p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Catatan tindak lanjut</p>
                            <p class="text-gray-700 dark:text-gray-300"><?php echo e($reservation->notes ?: 'Belum ada catatan dari petugas.'); ?></p>
                        </div>
                    </div>
                </div>

                <?php if(auth()->user()->hasAnyRole(['Admin', 'Teknisi'])): ?>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Tindak Lanjut oleh Teknisi / Admin</h2>
                        <form method="POST" action="<?php echo e(route('reservations.update', $reservation)); ?>" class="space-y-4" id="followUpForm">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <div>
                                <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white">
                                    <?php $__currentLoopData = \App\Models\Reservation::statusLabels(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>" <?php echo e($reservation->status === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label for="zoom_link" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Link Zoom</label>
                                <input id="zoom_link" type="url" name="zoom_link" value="<?php echo e(old('zoom_link', $reservation->zoom_link)); ?>" placeholder="https://zoom.us/j/..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                            </div>
                            <div>
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Catatan tindak lanjut</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Link aktif 10 menit sebelum mulai." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white"><?php echo e(old('notes', $reservation->notes)); ?></textarea>
                            </div>
                            <button type="submit" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-700">Simpan Follow Up</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-3 text-sm font-bold uppercase text-gray-500 dark:text-gray-400">Ringkasan</h3>
                    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Pemohon</p>
                            <p><?php echo e(optional($reservation->requester)->name ?? '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Ditangani oleh</p>
                            <p><?php echo e(optional($reservation->approver)->name ?? 'Belum ada petugas'); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Dibuat pada</p>
                            <p><?php echo e($reservation->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                            <p><?php echo e($reservation->updated_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
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
<?php endif; ?><?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views/reservations/show.blade.php ENDPATH**/ ?>