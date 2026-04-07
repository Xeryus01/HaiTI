<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TimCare - Sistem Helpdesk IT Terintegrasi</title>
    <meta name="description" content="Sistem helpdesk IT terintegrasi untuk pengelolaan tiket termasalahan, pengajuan ruang Zoom, dan layanan IT lainnya.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                get isDark() {
                    return this.theme === 'dark';
                },
                updateTheme() {
                    const html = document.documentElement;
                    if (this.isDark) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                    localStorage.setItem('theme', this.theme);
                },
                toggle() {
                    this.theme = this.isDark ? 'light' : 'dark';
                    this.updateTheme();
                }
            });
        });
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card:hover {
            transform: translateY(-4px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-dark-900" x-data x-init="$store.theme.init()">

    <!-- Navigation -->
    <nav class="bg-white dark:bg-dark-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-brand-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">TimCare</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="$store.theme.toggle()" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg x-show="!$store.theme.isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="$store.theme.isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-brand-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-brand-700">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Sistem Helpdesk IT<br>
                    <span class="text-brand-200">Terintegrasi</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200 max-w-3xl mx-auto">
                    Kelola tiket termasalahan IT, ajukan ruang Zoom, dan pantau layanan IT dengan mudah dan efisien.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('tickets.create') }}" class="bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Ajukan Tiket Baru
                        </a>
                        <a href="{{ route('reservations.create') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-colors">
                            Ajukan Ruang Zoom
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-colors">
                            Pelajari Lebih Lanjut
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white dark:bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Fitur Unggulan TimCare
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Tiket Permasalahan -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-brand-100 dark:bg-brand-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tiket Permasalahan IT</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Ajukan tiket termasalahan hardware, software, jaringan, dan masalah IT lainnya dengan mudah.
                    </p>
                </div>

                <!-- Pengajuan Zoom -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Pengajuan Ruang Zoom</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Ajukan ruang meeting Zoom dengan nota dinas dan dapatkan link meeting dari petugas IT.
                    </p>
                </div>

                <!-- Real-time Updates -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Update Real-time</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Terima notifikasi real-time tentang status tiket dan pengajuan melalui email dan dashboard.
                    </p>
                </div>

                <!-- Attachment Support -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Dukungan Lampiran</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Upload gambar, dokumen PDF, dan file pendukung lainnya untuk memudahkan penyelesaian masalah.
                    </p>
                </div>

                <!-- Role-based Access -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Akses Berbasis Role</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Sistem keamanan dengan role Admin, Teknisi, dan User untuk kontrol akses yang tepat.
                    </p>
                </div>

                <!-- Dashboard Analytics -->
                <div class="feature-card bg-gray-50 dark:bg-dark-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-500/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Dashboard Analytics</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Pantau performa layanan IT dengan dashboard yang menampilkan statistik dan ringkasan lengkap.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-slate-50 dark:bg-dark-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-brand-600 mb-3">Statistik</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Dampak TimCare</h2>
                <p class="max-w-2xl mx-auto text-gray-600 dark:text-gray-300">
                    Ringkasan kinerja sistem helpdesk IT yang menunjukkan aktivitas, penggunaan, dan layanan yang telah selesai.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-dark-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-brand-100 text-brand-600 mb-4">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-semibold text-gray-900 dark:text-white mb-2">{{ \App\Models\Ticket::count() }}</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tiket Ditangani</p>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-dark-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-green-100 text-green-600 mb-4">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-semibold text-gray-900 dark:text-white mb-2">{{ \App\Models\Reservation::count() }}</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ruang Zoom Diajukan</p>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-dark-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-semibold text-gray-900 dark:text-white mb-2">{{ \App\Models\User::count() }}</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pengguna Aktif</p>
                </div>

                <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-dark-800 p-6 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-purple-100 text-purple-600 mb-4">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-semibold text-gray-900 dark:text-white mb-2">
                        {{ \App\Models\Ticket::whereIn('status', [\App\Models\Ticket::STATUS_SOLVED, \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES])->count() + \App\Models\Reservation::where('status', 'COMPLETED')->count() }}
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Layanan Selesai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-brand-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Menggunakan TimCare?</h2>
            <p class="text-xl mb-8 text-brand-100">Bergabunglah dengan sistem helpdesk IT terintegrasi untuk kemudahan layanan IT Anda.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('tickets.create') }}" class="bg-white text-brand-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">Ajukan Tiket Sekarang</a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-brand-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">Daftar Akun</a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-brand-600 transition-colors">Masuk</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 dark:bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-3">
                <div>
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-brand-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="ml-3 text-xl font-bold">TimCare</span>
                    </div>
                    <p class="text-gray-400 max-w-md">
                        Layanan helpdesk IT modern untuk memudahkan tiket, pengajuan Zoom, dan pemantauan layanan secara terintegrasi.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-white">Fitur</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#features" class="hover:text-white">Tiket Permasalahan</a></li>
                        <li><a href="#features" class="hover:text-white">Pengajuan Zoom</a></li>
                        <li><a href="#features" class="hover:text-white">Dashboard Analytics</a></li>
                        <li><a href="#features" class="hover:text-white">Notifikasi Real-time</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-white">Dukungan</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#" class="hover:text-white">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                        <li><a href="#" class="hover:text-white">Kontak</a></li>
                        <li><a href="#" class="hover:text-white">Status Sistem</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-800 pt-8 text-sm text-gray-500">
                © {{ date('Y') }} TimCare. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
