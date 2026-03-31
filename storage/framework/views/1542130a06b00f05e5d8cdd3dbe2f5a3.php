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
    <div class="grid grid-cols-12 gap-4 md:gap-5.5 2xl:gap-7.5">
        <!-- Main Content -->
        <div class="col-span-12 lg:col-span-8">
            <div class="rounded-sm border border-gray-200 bg-white shadow-md dark:border-gray-700 dark:bg-dark-800">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6.5 py-4 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                <?php echo e($notification->title); ?>

                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <?php echo e($notification->created_at->format('M d, Y \a\t g:i A')); ?>

                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full <?php echo e($notification->is_read ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300'); ?> px-3 py-1 text-xs font-medium">
                            <?php echo e($notification->is_read ? 'Read' : 'Unread'); ?>

                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6.5 py-5">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</h3>
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                            <?php echo e($notification->message); ?>

                        </p>
                    </div>

                    <!-- Additional Details -->
                    <?php if($notification->action_type && $notification->action_id): ?>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Related Item</h3>
                            <?php switch($notification->type):
                                case ('ticket_created'): ?>
                                <?php case ('ticket_updated'): ?>
                                <?php case ('ticket_resolved'): ?>
                                    <?php $ticket = \App\Models\Ticket::findOrFail($notification->action_id) ?>
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Ticket</p>
                                        <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            <?php echo e($ticket->title); ?>

                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            #<?php echo e($ticket->id); ?> • Priority: <?php echo e($ticket->priority); ?>

                                        </p>
                                    </div>
                                    <?php break; ?>
                                <?php case ('reservation_created'): ?>
                                <?php case ('reservation_approved'): ?>
                                    <?php $reservation = \App\Models\Reservation::findOrFail($notification->action_id) ?>
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Reservation</p>
                                        <a href="<?php echo e(route('reservations.show', $reservation)); ?>" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            <?php echo e($reservation->room_name); ?>

                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            <?php echo e($reservation->start_time->format('M d, Y')); ?> • Status: <?php echo e($reservation->status); ?>

                                        </p>
                                    </div>
                                    <?php break; ?>
                                <?php case ('asset_created'): ?>
                                    <?php $asset = \App\Models\Asset::findOrFail($notification->action_id) ?>
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Asset</p>
                                        <a href="<?php echo e(route('assets.show', $asset)); ?>" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            <?php echo e($asset->name); ?>

                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            Code: <?php echo e($asset->asset_code); ?> • Status: <?php echo e($asset->status); ?>

                                        </p>
                                    </div>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Metadata -->
                    <?php if($notification->whatsapp_sent): ?>
                        <div class="border-t border-gray-200 dark:border-gray-700 mt-5 pt-5">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">WhatsApp Status</h3>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-2 w-2 rounded-full <?php echo e($notification->whatsapp_status === 'delivered' ? 'bg-green-500' : 'bg-gray-400'); ?>"></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    <?php echo e($notification->whatsapp_status ? ucfirst($notification->whatsapp_status) : 'Sending...'); ?>

                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer Actions -->
                <div class="border-t border-gray-200 px-6.5 py-4 dark:border-gray-700 flex gap-2">
                    <a href="<?php echo e(route('notifications.index')); ?>" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-2 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-white/5">
                        Back to Notifications
                    </a>
                    <?php if(!$notification->is_read): ?>
                        <button onclick="fetch('/api/notifications/<?php echo e($notification->id); ?>/mark-as-read', {method: 'PATCH'}).then(r => location.reload())" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                            Mark as Read
                        </button>
                    <?php endif; ?>
                    <button onclick="if(confirm('Delete this notification?')) fetch('/api/notifications/<?php echo e($notification->id); ?>', {method: 'DELETE'}).then(r => window.location.href = '/notifications')" class="inline-flex items-center justify-center rounded-lg border border-red-300 px-5 py-2.5 text-center font-medium text-red-600 hover:bg-red-50 dark:border-red-600/50 dark:text-red-400 dark:hover:bg-red-600/10">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Info Card -->
            <div class="rounded-sm border border-gray-200 bg-white px-6.5 py-5 shadow-md dark:border-gray-700 dark:bg-dark-800">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Type</p>
                        <span class="inline-block mt-1 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <?php echo e(ucfirst(str_replace('_', ' ', $notification->type))); ?>

                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Date</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            <?php echo e($notification->created_at->format('M d, Y')); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Time</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            <?php echo e($notification->created_at->format('g:i A')); ?>

                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Status</p>
                        <span class="inline-block mt-1 rounded-full <?php echo e($notification->is_read ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300'); ?> px-3 py-1 text-xs font-medium">
                            <?php echo e($notification->is_read ? 'Read' : 'Unread'); ?>

                        </span>
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
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views\notifications\show.blade.php ENDPATH**/ ?>