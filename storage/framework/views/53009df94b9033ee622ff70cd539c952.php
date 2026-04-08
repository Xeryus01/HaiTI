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
<main class="p-6">
    <div class="max-w-2xl mx-auto">
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('users.index')); ?>" class="mr-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail User</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informasi lengkap user <?php echo e($user->name); ?></p>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="bg-white dark:bg-dark-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($user->name); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($user->email); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Handphone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($user->phone_number ?? '-'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                <dd class="mt-1">
                                    <?php if($user->hasRole('Admin')): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Admin
                                        </span>
                                    <?php elseif($user->hasRole('Teknisi')): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Teknisi
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            User
                                        </span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Pada</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($user->created_at->format('d F Y, H:i')); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($user->updated_at->format('d F Y, H:i')); ?></dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-gray-50 dark:bg-dark-700 px-6 py-4">
                        <div class="flex justify-end space-x-3">
                            <a href="<?php echo e(route('users.edit', $user)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit User
                            </a>
                            <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Hapus User
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views/users/show.blade.php ENDPATH**/ ?>