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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aset <?php echo e($asset->asset_code); ?>

            </h2>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('assets.edit', $asset)); ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    Ubah
                </a>
                <form action="<?php echo e(route('assets.destroy', $asset)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" onclick="return confirm('Hapus aset ini?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4"><?php echo e($asset->name); ?></h3>
                    <p class="text-gray-600 mb-6">Kode Aset: <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono"><?php echo e($asset->asset_code); ?></code></p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Tipe</label>
                            <p class="text-gray-800 mt-1 font-medium"><?php echo e($asset->type); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Merek</label>
                            <p class="text-gray-800 mt-1"><?php echo e($asset->brand ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Model</label>
                            <p class="text-gray-800 mt-1"><?php echo e($asset->model ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Nomor Seri</label>
                            <p class="text-gray-800 mt-1"><?php echo e($asset->serial_number ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Lokasi</label>
                            <p class="text-gray-800 mt-1"><?php echo e($asset->location ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Tanggal Dibeli</label>
                            <p class="text-gray-800 mt-1"><?php echo e(optional($asset->purchased_at)->format('d/m/Y') ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Spesifikasi</h4>
                        <?php if($asset->specs && is_array($asset->specs) && count($asset->specs) > 0): ?>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $asset->specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex justify-between">
                                        <span class="text-gray-700 capitalize"><?php echo e(str_replace('_', ' ', $key)); ?>:</span>
                                        <span class="text-gray-900 font-medium"><?php echo e($value); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">Belum ada spesifikasi tercatat</p>
                        <?php endif; ?>
                    </div>

                    <hr class="my-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Perawatan</h4>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $asset->logs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800"><?php echo e($log->action ?? 'Action'); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo e(optional($log->created_at)->diffForHumans() ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <p class="mt-2 text-gray-700"><?php echo e($log->description ?? ''); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-gray-500">Belum ada riwayat perawatan</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Aset</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Status Saat Ini</label>
                            <p class="text-lg font-bold mt-1">
                                <span class="px-3 py-1 rounded-full text-sm
                                    <?php if($asset->status === 'ACTIVE'): ?>bg-green-100 text-green-700
                                    <?php elseif($asset->status === 'MAINTENANCE'): ?>bg-yellow-100 text-yellow-700
                                    <?php elseif($asset->status === 'BROKEN'): ?>bg-red-100 text-red-700
                                    <?php else: ?> bg-gray-100 text-gray-700
                                    <?php endif; ?>">
                                    <?php echo e($asset->status_label); ?>

                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Pemegang</label>
                            <p class="text-gray-800 font-medium mt-1"><?php echo e($asset->holder ?? 'Belum Ditugaskan'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Kondisi</label>
                            <p class="text-gray-800 font-medium mt-1"><?php echo e($asset->condition_label); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Tanggal Perolehan</label>
                            <p class="text-gray-800 font-medium mt-1"><?php echo e(optional($asset->purchased_at)->format('d/m/Y') ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Dibuat</label>
                            <p class="text-gray-800 font-medium mt-1"><?php echo e($asset->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Terakhir Diperbarui</label>
                            <p class="text-gray-800 font-medium mt-1"><?php echo e($asset->updated_at->format('d/m/Y H:i')); ?></p>
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
<?php endif; ?>
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views/assets/show.blade.php ENDPATH**/ ?>