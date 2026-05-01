<x-app-layout>
@php
    $user = auth()->user();
    $isAdminOrTechnician = $user->hasRole(['Admin', 'Teknisi']);

    if ($isAdminOrTechnician) {
        $ticketCounts = [
            'Dibuka' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Menunggu Barang' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_WAITING_PARTS)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Batal' => \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_REJECTED, \App\Models\Ticket::STATUS_CANCELLED])->count(),
        ];
        $zoomCounts = [
            'Dibuka' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_PENDING)->count(),
            'Diproses Teknisi' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_APPROVED)->count(),
            'Menunggu Monitoring' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_WAITING_MONITORING)->count(),
            'Selesai' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_COMPLETED)->count(),
            'Selesai Ditolak' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_REJECTED)->count(),
            'Batal' => \App\Models\Reservation::where('status', \App\Models\Reservation::STATUS_CANCELLED)->count(),
        ];
        $layananSelesai = \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('status', 'COMPLETED')->count();
        $recentTickets = \App\Models\Ticket::latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::latest()->take(5)->get();
    } else {
        $ticketCounts = [
            'Dibuka' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_OPEN)->count(),
            'Diproses Teknisi' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_ASSIGNED_DETECT)->count(),
            'Menunggu Barang' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_WAITING_PARTS)->count(),
            'Selesai + Catatan' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
            'Selesai' => \App\Models\Ticket::where('requester_id', $user->id)->where('status', \App\Models\Ticket::STATUS_SOLVED)->count(),
            'Batal' => \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_REJECTED, \App\Models\Ticket::STATUS_CANCELLED])->count(),
        ];
        $zoomCounts = [
            'Dibuka' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_PENDING)->count(),
            'Diproses Teknisi' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_APPROVED)->count(),
            'Menunggu Monitoring' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_WAITING_MONITORING)->count(),
            'Selesai' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_COMPLETED)->count(),
            'Selesai Ditolak' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_REJECTED)->count(),
            'Batal' => \App\Models\Reservation::where('requester_id', $user->id)->where('status', \App\Models\Reservation::STATUS_CANCELLED)->count(),
        ];
        $layananSelesai = \App\Models\Ticket::where('requester_id', $user->id)->whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count()
            + \App\Models\Reservation::where('requester_id', $user->id)->where('status', 'COMPLETED')->count();
        $recentTickets = \App\Models\Ticket::where('requester_id', $user->id)->latest()->take(5)->get();
        $recentReservations = \App\Models\Reservation::where('requester_id', $user->id)->latest()->take(5)->get();
    }

    $ticketTotal = array_sum($ticketCounts);
    $zoomTotal = array_sum($zoomCounts);
    $ticketResolved = $ticketCounts['Selesai'] + $ticketCounts['Selesai + Catatan'];
    $zoomCompleted = $zoomCounts['Selesai'];
    $ticketCompletionPercent = $ticketTotal > 0 ? round($ticketResolved * 100 / $ticketTotal) : 0;
    $zoomCompletionPercent = $zoomTotal > 0 ? round($zoomCompleted * 100 / $zoomTotal) : 0;
    $totalServices = $ticketTotal + $zoomTotal;
    $serviceCompletionPercent = $totalServices > 0 ? round(($ticketResolved + $zoomCompleted) * 100 / $totalServices) : 0;
    $openTicketCount = $ticketCounts['Dibuka'] ?? 0;
    $pendingZoomCount = $zoomCounts['Dibuka'] ?? 0;

    $ticketStatusColors = [
        'Dibuka'           => ['dot' => 'bg-amber-400', 'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300'],
        'Diproses Teknisi' => ['dot' => 'bg-blue-400',  'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300'],
        'Menunggu Barang'  => ['dot' => 'bg-violet-400','badge' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300'],
        'Selesai + Catatan'=> ['dot' => 'bg-teal-400',  'badge' => 'bg-teal-50 text-teal-700 dark:bg-teal-500/10 dark:text-teal-300'],
        'Selesai'          => ['dot' => 'bg-emerald-500','badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'],
        'Batal'            => ['dot' => 'bg-slate-400',  'badge' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'],
    ];

    $zoomStatusColors = [
        'Dibuka'            => ['dot' => 'bg-amber-400',  'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300'],
        'Diproses Teknisi'  => ['dot' => 'bg-blue-400',   'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300'],
        'Menunggu Monitoring'=> ['dot' => 'bg-violet-400','badge' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300'],
        'Selesai'           => ['dot' => 'bg-emerald-500','badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'],
        'Selesai Ditolak'   => ['dot' => 'bg-rose-400',   'badge' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300'],
        'Batal'             => ['dot' => 'bg-slate-400',  'badge' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'],
    ];
@endphp

<style>
    .stat-card { transition: box-shadow 0.18s, transform 0.18s; }
    .stat-card:hover { box-shadow: 0 8px 30px rgba(99,102,241,0.10); transform: translateY(-1px); }
    .progress-bar { transition: width 0.7s cubic-bezier(.4,0,.2,1); }
    .badge-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .section-label { letter-spacing: 0.18em; font-size: 9.5px; font-weight: 700; text-transform: uppercase; }
    .scroll-thin::-webkit-scrollbar { width: 4px; }
    .scroll-thin::-webkit-scrollbar-track { background: transparent; }
    .scroll-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    .dark .scroll-thin::-webkit-scrollbar-thumb { background: #334155; }
    .fade-in { animation: fadeIn 0.35s ease both; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
    .chart-card canvas { display: block; }
</style>

<div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto max-w-screen-xl px-4 py-5 sm:px-6 lg:px-8 lg:py-7">

        {{-- ===== HEADER ===== --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between fade-in">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">Ringkasan Layanan</h1>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Halo, <span class="font-medium text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span>. Pantau tiket, Zoom, dan performa layanan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url()->to(route('tickets.create')) }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition duration-200 hover:-translate-y-0.5 hover:bg-brand-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Ajukan Tiket
                </a>
                <a href="{{ url()->to(route('reservations.create')) }}"
                   class="inline-flex items-center gap-2 rounded-2xl border border-brand-600 bg-white px-4 py-2.5 text-sm font-semibold text-brand-600 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-brand-700 hover:bg-brand-50 dark:bg-slate-900 dark:text-brand-400 dark:border-brand-500 dark:hover:bg-brand-500/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.07A1 1 0 0121 8.88V15.12a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    Ajukan Zoom
                </a>
            </div>
        </div>

        {{-- ===== TOP STAT STRIP ===== --}}
        <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2 fade-in" style="animation-delay:.05s">
            @php
                $topStats = [
                    ['label'=>'Total Tiket',    'value'=>$ticketTotal,    'sub'=>'Semua permintaan tiket',    'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color'=>'text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-300'],
                    ['label'=>'Total Zoom',     'value'=>$zoomTotal,      'sub'=>'Reservasi Zoom saat ini',   'icon'=>'M15 10l4.553-2.07A1 1 0 0121 8.88V15.12a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z', 'color'=>'text-violet-600 bg-violet-50 dark:bg-violet-500/10 dark:text-violet-300'],
                    ['label'=>'Layanan Selesai','value'=>$layananSelesai, 'sub'=>'Total layanan selesai',     'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-300'],
                    ['label'=>'Capaian',        'value'=>$serviceCompletionPercent.'%', 'sub'=>'Persentase penyelesaian', 'icon'=>'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'color'=>'text-sky-600 bg-sky-50 dark:bg-sky-500/10 dark:text-sky-300'],
                ];
            @endphp
            @foreach ($topStats as $s)
            <div class="stat-card rounded-3xl border border-slate-200 bg-white p-3 shadow-sm transition-shadow hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.28em] text-slate-500 dark:text-slate-400">{{ $s['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $s['value'] }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $s['sub'] }}</p>
                    </div>
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-3xl {{ $s['color'] }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ===== QUICK INSIGHTS ===== --}}
        <div class="mb-5 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm transition-shadow hover:shadow-lg dark:border-slate-700 dark:bg-slate-900 fade-in" style="animation-delay:.1s">
            <div class="mb-3 flex items-center justify-between">
                <div>
                    <span class="section-label text-slate-500 dark:text-slate-400">Ringkasan Cepat</span>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Fokus layanan hari ini</h2>
                </div>
                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Terkini</span>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Tiket menunggu</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $openTicketCount }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Permintaan tiket yang perlu penanganan.</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Zoom menunggu</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $pendingZoomCount }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Pengajuan Zoom belum disetujui atau diproses.</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Total layanan</p>
                    <p class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $totalServices }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Jumlah tiket dan Zoom yang terdaftar saat ini.</p>
                </div>
            </div>
        </div>

        <!-- {{-- ===== STATUS CARDS ===== --}}
        <div class="mb-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3 fade-in" style="animation-delay:.1s">

            {{-- Tiket Status --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-sm transition-shadow hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <span class="section-label text-indigo-500 dark:text-indigo-400">Tiket Permasalahan</span>
                        <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $ticketTotal }}</p>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-3xl bg-indigo-50 dark:bg-indigo-500/10">
                        <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach ($ticketCounts as $label => $count)
                    @php $c = $ticketStatusColors[$label] ?? ['dot'=>'bg-slate-400','badge'=>'bg-slate-100 text-slate-600']; @endphp
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="badge-dot {{ $c['dot'] }}"></span>
                            <span class="truncate text-sm text-slate-600 dark:text-slate-300">{{ $label }}</span>
                        </div>
                        <span class="ml-2 flex-shrink-0 rounded-full {{ $c['badge'] }} px-2.5 py-1 text-sm font-semibold">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Zoom Status --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-sm transition-shadow hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <span class="section-label text-violet-500 dark:text-violet-400">Pengajuan Zoom</span>
                        <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $zoomTotal }}</p>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-3xl bg-violet-50 dark:bg-violet-500/10">
                        <svg class="h-4 w-4 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.07A1 1 0 0121 8.88V15.12a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach ($zoomCounts as $label => $count)
                    @php $c = $zoomStatusColors[$label] ?? ['dot'=>'bg-slate-400','badge'=>'bg-slate-100 text-slate-600']; @endphp
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="badge-dot {{ $c['dot'] }}"></span>
                            <span class="truncate text-sm text-slate-600 dark:text-slate-300">{{ $label }}</span>
                        </div>
                        <span class="ml-2 flex-shrink-0 rounded-full {{ $c['badge'] }} px-2.5 py-1 text-sm font-semibold">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Progress Summary --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-sm transition-shadow hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <span class="section-label text-emerald-500 dark:text-emerald-400">Capaian Layanan</span>
                        <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $layananSelesai }} <span class="text-sm font-normal text-slate-400">selesai</span></p>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-3xl bg-emerald-50 dark:bg-emerald-500/10">
                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Keseluruhan</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $serviceCompletionPercent }}%</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="progress-bar h-2.5 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500" style="width:{{ $serviceCompletionPercent }}%"></div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <div class="mb-2 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="badge-dot bg-indigo-400"></span>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Tiket selesai</span>
                            </div>
                            <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ $ticketCompletionPercent }}%</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="progress-bar h-2.5 rounded-full bg-gradient-to-r from-indigo-400 to-blue-500" style="width:{{ $ticketCompletionPercent }}%"></div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                        <div class="mb-2 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="badge-dot bg-violet-400"></span>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Zoom selesai</span>
                            </div>
                            <span class="text-sm font-semibold text-violet-600 dark:text-violet-400">{{ $zoomCompletionPercent }}%</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                            <div class="progress-bar h-2.5 rounded-full bg-gradient-to-r from-violet-400 to-purple-500" style="width:{{ $zoomCompletionPercent }}%"></div>
                        </div>
                    </div>
                </div>

                <p class="mt-3 text-xs text-slate-400 dark:text-slate-500">Total {{ $totalServices }} layanan terdaftar.</p>
            </div>
        </div> -->

        {{-- ===== CHARTS ===== --}}
        <div class="mb-5 grid gap-3 lg:grid-cols-2 fade-in" style="animation-delay:.15s">
            <div class="chart-card rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <span class="section-label text-indigo-500 dark:text-indigo-400">Grafik Tiket</span>
                    </div>
                    <a href="{{ url()->to(route('exports.tickets')) }}"
                       class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Ekspor CSV
                    </a>
                </div>
                <div class="relative h-64">
                    <canvas id="ticketChart"></canvas>
                </div>
            </div>

            <div class="chart-card rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <span class="section-label text-violet-500 dark:text-violet-400">Grafik Pengajuan Zoom</span>
                    </div>
                    <a href="{{ url()->to(route('exports.reservations')) }}"
                       class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Ekspor CSV
                    </a>
                </div>
                <div class="relative h-64">
                    <canvas id="zoomChart"></canvas>
                </div>
            </div>
        </div>

        {{-- ===== RECENT TABLES ===== --}}
        <div class="grid gap-3 lg:grid-cols-2 fade-in" style="animation-delay:.2s">

            {{-- Recent Tickets --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-50 dark:bg-indigo-500/10">
                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </span>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Tiket Terbaru</h3>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-brand-600 hover:underline">Lihat semua</a>
                </div>
                <div class="scroll-thin max-h-72 divide-y divide-gray-200 overflow-y-auto dark:divide-slate-800">
                    @forelse ($recentTickets as $ticket)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 dark:bg-indigo-500/10">
                            <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-1.414a2 2 0 01.586-1.414z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ $ticket->title }}</p>
                            <p class="truncate text-xs text-slate-400">{{ $ticket->code }} &bull; {{ $ticket->category_label }}</p>
                        </div>
                        <span class="flex-shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $ticket->status_label }}</span>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <svg class="mb-2 h-8 w-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Belum ada tiket</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Reservations --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-violet-50 dark:bg-violet-500/10">
                            <svg class="h-3.5 w-3.5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.07A1 1 0 0121 8.88V15.12a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                        </span>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Pengajuan Zoom Terbaru</h3>
                    </div>
                    <a href="{{ route('reservations.index') }}" class="text-sm font-medium text-brand-600 hover:underline">Lihat semua</a>
                </div>
                <div class="scroll-thin max-h-72 divide-y divide-gray-200 overflow-y-auto dark:divide-slate-800">
                    @forelse ($recentReservations as $reservation)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-violet-50 dark:bg-violet-500/10">
                            <svg class="h-3.5 w-3.5 text-violet-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ $reservation->room_name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ optional($reservation->start_time)->format('d/m/Y H:i') }} &bull; {{ \Illuminate\Support\Str::limit($reservation->purpose, 38) }}</p>
                        </div>
                        <span class="flex-shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $reservation->status_label }}</span>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <svg class="mb-2 h-8 w-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.07A1 1 0 0121 8.88V15.12a1 1 0 01-1.447.89L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Belum ada pengajuan Zoom</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const tickColor = isDark ? '#94a3b8' : '#64748b';
    const font = { family: 'inherit', size: 11 };

    const ticketCtx = document.getElementById('ticketChart');
    if (ticketCtx) {
        new Chart(ticketCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($ticketCounts)),
                datasets: [{
                    label: 'Tiket',
                    data: @json(array_values($ticketCounts)),
                    backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#34d399','#10b981','#94a3b8'],
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.65,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { cornerRadius: 8, padding: 10 } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: tickColor, font } },
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, precision: 0, stepSize: 1, font } }
                }
            }
        });
    }

    const zoomCtx = document.getElementById('zoomChart');
    if (zoomCtx) {
        new Chart(zoomCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($zoomCounts)),
                datasets: [{
                    data: @json(array_values($zoomCounts)),
                    backgroundColor: ['#fbbf24','#60a5fa','#a78bfa','#10b981','#f87171','#94a3b8'],
                    hoverOffset: 6,
                    borderWidth: 2,
                    borderColor: isDark ? '#1e293b' : '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 14, color: tickColor, font }
                    },
                    tooltip: { cornerRadius: 8, padding: 10 }
                }
            }
        });
    }
});
</script>
</x-app-layout>