<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Breadcrumb & Page Title -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-4">
            <!-- Total Assets -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Assets</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Asset::count() }}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-500/15">
                        <svg class="fill-blue-600 dark:fill-blue-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 4C3 2.89543 3.89543 2 5 2H19C20.1046 2 21 2.89543 21 4V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V4Z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Assets -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Assets</p>
                        <h3 class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Asset::where('status', 'ACTIVE')->count() }}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-500/15">
                        <svg class="fill-green-600 dark:fill-green-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM16.5303 9.46967C16.8232 9.76256 16.8232 10.2374 16.5303 10.5303L10.5303 16.5303C10.2374 16.8232 9.76256 16.8232 9.46967 16.5303L7.46967 14.5303C7.17678 14.2374 7.17678 13.7626 7.46967 13.4697C7.76256 13.1768 8.23744 13.1768 8.53033 13.4697L10 14.9393L15.4697 9.46967C15.7626 9.17678 16.2374 9.17678 16.5303 9.46967Z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tickets</p>
                        <h3 class="mt-2 text-3xl font-bold text-orange-600 dark:text-orange-400">{{ \App\Models\Ticket::count() }}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-500/15">
                        <svg class="fill-orange-600 dark:fill-orange-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 3C2.89543 3 2 3.89543 2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5C22 3.89543 21.1046 3 20 3H4ZM4 5H20V19H4V5Z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Open Tickets -->
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-dark-800 md:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Tickets</p>
                        <h3 class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ \App\Models\Ticket::where('status', 'OPEN')->count() }}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-500/15">
                        <svg class="fill-red-600 dark:fill-red-400" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 11C12.5523 11 13 11.4477 13 12V15C13 15.5523 12.5523 16 12 16C11.4477 16 11 15.5523 11 15V12C11 11.4477 11.4477 11 12 11ZM12 7C12.5523 7 13 7.44772 13 8C13 8.55228 12.5523 9 12 9C11.4477 9 11 8.55228 11 8C11 7.44772 11.4477 7 12 7Z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Tickets -->
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800">
            <div class="borders-gray-200 border-b px-5 py-4 dark:border-gray-700 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Latest Tickets</h3>
                    <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">View All</a>
                </div>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse (\App\Models\Ticket::latest()->take(5)->get() as $ticket)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-white/5 sm:px-6">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $ticket->title }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $ticket->code }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            @if($ticket->priority === 'CRITICAL') bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                            @elseif($ticket->priority === 'HIGH') bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400
                            @elseif($ticket->priority === 'MEDIUM') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                            @else bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                            @endif">
                            {{ $ticket->priority }}
                        </span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                            @if($ticket->status === 'OPEN') bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                            @elseif($ticket->status === 'IN_PROGRESS') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                            @elseif($ticket->status === 'RESOLVED') bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                            @endif">
                            {{ $ticket->status }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center sm:px-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">No tickets yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
</x-app-layout>
