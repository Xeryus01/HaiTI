<x-app-layout>
    <div class="rounded-sm border border-gray-200 px-4 py-6 shadow-md sm:px-7.5 dark:border-gray-700 dark:bg-dark-800">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Notifications
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    You have <span class="font-medium">{{ auth()->user()->notifications()->where('is_read', false)->count() }}</span> unread notifications
                </p>
            </div>
            @if (auth()->user()->notifications()->where('is_read', false)->exists())
                <form action="{{ route('api.notifications.markAllAsRead') }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="button" onclick="fetch('/api/notifications/mark-all-as-read', {method: 'PATCH'}).then(r => location.reload())" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-center font-medium text-white hover:bg-brand-700">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="overflow-x-auto">
            @if ($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach ($notifications as $notification)
                        <div class="flex items-start gap-4 p-4 rounded-lg border border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-white/5 transition {{ !$notification->is_read ? 'bg-blue-50/50 dark:bg-blue-500/10' : '' }}">
                            <!-- Unread Indicator -->
                            <div class="mt-1">
                                @if (!$notification->is_read)
                                    <div class="h-3 w-3 rounded-full bg-brand-500"></div>
                                @else
                                    <div class="h-3 w-3 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <a href="{{ route('notifications.show', $notification) }}" class="block">
                                    <h4 class="font-medium text-gray-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-400 transition">
                                        {{ $notification->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->message }}
                                    </p>
                                </a>

                                <!-- Meta Info -->
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if ($notification->action_type && $notification->action_id)
                                        @switch($notification->action_type)
                                            @case('ticket')
                                                <a href="{{ route('tickets.show', $notification->action_id) }}" class="text-xs text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                                    View Ticket
                                                </a>
                                                @break
                                            @case('reservation')
                                                <a href="{{ route('reservations.show', $notification->action_id) }}" class="text-xs text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                                    View Reservation
                                                </a>
                                                @break
                                            @case('asset')
                                                <a href="{{ route('assets.show', $notification->action_id) }}" class="text-xs text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                                                    View Asset
                                                </a>
                                                @break
                                        @endswitch
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                @if (!$notification->is_read)
                                    <button onclick="fetch('/api/notifications/{{ $notification->id }}/mark-as-read', {method: 'PATCH'}).then(r => location.reload())" class="inline-flex h-8 w-8 items-center justify-center rounded text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5" title="Mark as read">
                                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.8333 4.16667L6 12L2.16667 8.16667" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="if(confirm('Delete this notification?')) fetch('/api/notifications/{{ $notification->id }}', {method: 'DELETE'}).then(r => location.reload())" class="inline-flex h-8 w-8 items-center justify-center rounded text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-red-600 dark:hover:text-red-400" title="Delete">
                                    <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.5 3.5L3.5 12.5M3.5 3.5L12.5 12.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($notifications->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 14.5V17c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h10c.55 0 1 .45 1 1v2.5M9 3v3M9 21v-2M3 9h8M3 13h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-base font-medium text-gray-900 dark:text-white">No notifications yet</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You're all caught up!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
