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
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Support Tickets</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track all IT support requests</p>
            </div>
            <a href="<?php echo e(route('tickets.create')); ?>" class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 3C10.5523 3 11 3.44772 11 4V9H16C16.5523 9 17 9.44772 17 10C17 10.5523 16.5523 11 16 11H11V16C11 16.5523 10.5523 17 10 17C9.44772 17 9 16.5523 9 16V11H4C3.44772 11 3 10.5523 3 10C3 9.44772 3.44772 9 4 9H9V4C9 3.44772 9.44772 3 10 3Z"></path>
                </svg>
                New Ticket
            </a>
        </div>

        <!-- Filter Bar -->
        <form method="GET" class="mb-4 flex flex-wrap gap-3">
            <select name="status" class="rounded border-gray-300 px-3 py-2">
                <option value="">All statuses</option>
                <?php $__currentLoopData = \App\Models\Ticket::statuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php echo e(request('status') === $st ? 'selected' : ''); ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="priority" class="rounded border-gray-300 px-3 py-2">
                <option value="">All priorities</option>
                <?php $__currentLoopData = ['LOW','MEDIUM','HIGH','CRITICAL']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p); ?>" <?php echo e(request('priority') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="rounded bg-brand-600 px-4 py-2 text-white">Filter</button>
        </form>

        <!-- Tickets Table -->
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
            <!-- Table Header -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-white/5">
                        <tr>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Code</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Title</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Category</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Assignee</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Priority</span>
                            </th>
                            <th class="px-5 py-3.5 text-right sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Action</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-semibold text-brand-600 dark:text-brand-400"><?php echo e($ticket->code); ?></span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white"><?php echo e($ticket->title); ?></span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($ticket->category); ?></span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white"><?php echo e(optional($ticket->assignee)->name ?? '—'); ?></span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        <?php if($ticket->status === \App\Models\Ticket::STATUS_OPEN): ?> bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        <?php elseif($ticket->status === \App\Models\Ticket::STATUS_ASSIGNED_DETECT): ?> bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        <?php elseif($ticket->status === \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES): ?> bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400
                                        <?php elseif($ticket->status === \App\Models\Ticket::STATUS_SOLVED): ?> bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        <?php else: ?> bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400
                                        <?php endif; ?>">
                                        <?php echo e($ticket->status); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        <?php if($ticket->priority === 'CRITICAL'): ?> bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        <?php elseif($ticket->priority === 'HIGH'): ?> bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400
                                        <?php elseif($ticket->priority === 'MEDIUM'): ?> bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        <?php else: ?> bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        <?php endif; ?>">
                                        <?php echo e($ticket->priority); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right sm:px-6">
                                    <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">View</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center sm:px-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No tickets found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($tickets->hasPages()): ?>
                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700 sm:px-6">
                    <?php echo e($tickets->links()); ?>

                </div>
            <?php endif; ?>
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
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views\tickets\index.blade.php ENDPATH**/ ?>