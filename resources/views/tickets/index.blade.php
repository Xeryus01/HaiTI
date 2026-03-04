<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Support Tickets</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and track all IT support requests</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700">
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
                @foreach(\App\Models\Ticket::statuses() as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
            <select name="priority" class="rounded border-gray-300 px-3 py-2">
                <option value="">All priorities</option>
                @foreach(['LOW','MEDIUM','HIGH','CRITICAL'] as $p)
                    <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
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
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-semibold text-brand-600 dark:text-brand-400">{{ $ticket->code }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $ticket->title }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->category }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ optional($ticket->assignee)->name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @if($ticket->status === \\App\\Models\\Ticket::STATUS_OPEN) bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        @elseif($ticket->status === \\App\\Models\\Ticket::STATUS_ASSIGNED_DETECT) bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        @elseif($ticket->status === \\App\\Models\\Ticket::STATUS_SOLVED_WITH_NOTES) bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400
                                        @elseif($ticket->status === \\App\\Models\\Ticket::STATUS_SOLVED) bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400
                                        @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @if($ticket->priority === 'CRITICAL') bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        @elseif($ticket->priority === 'HIGH') bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400
                                        @elseif($ticket->priority === 'MEDIUM') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        @else bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        @endif">
                                        {{ $ticket->priority }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right sm:px-6">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center sm:px-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No tickets found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700 sm:px-6">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
