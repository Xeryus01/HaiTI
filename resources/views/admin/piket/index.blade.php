<x-app-layout>
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Manajemen Jadwal Piket</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola jadwal piket tim IT untuk setiap bulan</p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-400 bg-green-100 p-4 text-green-700 dark:border-green-500/30 dark:bg-green-500/15 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <!-- Calendar Grid -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @php
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $currentMonth = date('n');
        @endphp

            @foreach($months as $monthNum => $monthName)
                @php
                    $schedule = $schedules->firstWhere('month', $monthNum);
                    $isCurrentMonth = $monthNum == $currentMonth;
                @endphp
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition-shadow dark:border-gray-700 dark:bg-dark-800 {{ $isCurrentMonth ? 'ring-2 ring-brand-500' : '' }}">
                    <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white sm:text-xl">{{ $monthName }}</h3>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Lantai 1:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->lantai_1 ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Lantai 2:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->lantai_2 ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-white/5">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">TU:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $schedule->tu ?? '-' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('piket.edit', $monthNum) }}" class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700 transition">
                        Edit
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
</x-app-layout>
