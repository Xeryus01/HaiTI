<x-app-layout>
    <div class="ml-64 min-h-screen">
        <div class="p-5 sm:p-7.5 lg:p-9">
            <div class="grid grid-cols-12 gap-4 md:gap-5.5 2xl:gap-7.5">
                <!-- Main Content -->
        <div class="col-span-12 lg:col-span-8">
            <div class="rounded-sm border border-gray-200 bg-white shadow-md dark:border-gray-700 dark:bg-dark-800">
                <!-- Header -->
                <div class="border-b border-gray-200 px-6.5 py-4 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $notification->created_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300' }} px-3 py-1 text-xs font-medium">
                            {{ $notification->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6.5 py-5">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</h3>
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                            {{ $notification->message }}
                        </p>
                    </div>

                    <!-- Additional Details -->
                    @if ($notification->action_type && $notification->action_id)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Related Item</h3>
                            @switch($notification->type)
                                @case('ticket_created')
                                @case('ticket_updated')
                                @case('ticket_resolved')
                                    @php $ticket = \App\Models\Ticket::findOrFail($notification->action_id) @endphp
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Ticket</p>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            {{ $ticket->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            #{{ $ticket->id }} • Priority: {{ $ticket->priority }}
                                        </p>
                                    </div>
                                    @break
                                @case('reservation_created')
                                @case('reservation_approved')
                                    @php $reservation = \App\Models\Reservation::findOrFail($notification->action_id) @endphp
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Reservation</p>
                                        <a href="{{ route('reservations.show', $reservation) }}" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            {{ $reservation->room_name }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            {{ $reservation->start_time->format('M d, Y') }} • Status: {{ $reservation->status }}
                                        </p>
                                    </div>
                                    @break
                                @case('asset_created')
                                    @php $asset = \App\Models\Asset::findOrFail($notification->action_id) @endphp
                                    <div class="rounded-lg bg-gray-50 dark:bg-white/5 p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Asset</p>
                                        <a href="{{ route('assets.show', $asset) }}" class="text-base font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 mt-1">
                                            {{ $asset->name }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            Code: {{ $asset->asset_code }} • Status: {{ $asset->status }}
                                        </p>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    @endif

                    <!-- Metadata -->
                    @if ($notification->whatsapp_sent)
                        <div class="border-t border-gray-200 dark:border-gray-700 mt-5 pt-5">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">WhatsApp Status</h3>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-2 w-2 rounded-full {{ $notification->whatsapp_status === 'delivered' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $notification->whatsapp_status ? ucfirst($notification->whatsapp_status) : 'Sending...' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="border-t border-gray-200 px-6.5 py-4 dark:border-gray-700 flex gap-2">
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-2 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-white/5">
                        Back to Notifications
                    </a>
                    @if (!$notification->is_read)
                        <button onclick="fetch('/api/notifications/{{ $notification->id }}/mark-as-read', {method: 'PATCH'}).then(r => location.reload())" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                            Mark as Read
                        </button>
                    @endif
                    <button onclick="if(confirm('Delete this notification?')) fetch('/api/notifications/{{ $notification->id }}', {method: 'DELETE'}).then(r => window.location.href = '/notifications')" class="inline-flex items-center justify-center rounded-lg border border-red-300 px-5 py-2.5 text-center font-medium text-red-600 hover:bg-red-50 dark:border-red-600/50 dark:text-red-400 dark:hover:bg-red-600/10">
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
                            {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Date</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $notification->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Time</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            {{ $notification->created_at->format('g:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Status</p>
                        <span class="inline-block mt-1 rounded-full {{ $notification->is_read ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300' }} px-3 py-1 text-xs font-medium">
                            {{ $notification->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
