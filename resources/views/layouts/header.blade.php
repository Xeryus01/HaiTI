<!-- Header -->
<header class="sticky top-0 z-40 flex items-center justify-between border-b border-gray-200 bg-white px-5.5 py-4 dark:border-gray-700 dark:bg-dark-800 sm:px-7.5 lg:px-9">
    <!-- Left Section -->
    <div class="flex items-center gap-4">
        <button @click="$store.theme.toggle()" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
            <!-- Sun Icon (shown in dark mode) -->
            <svg x-show="$store.theme.isDark" class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 2C8.55228 2 9 1.55228 9 1C9 0.447715 8.55228 0 8 0C7.44772 0 7 0.447715 7 1C7 1.55228 7.44772 2 8 2Z"></path>
                <path d="M8 14C7.44772 14 7 14.4477 7 15C7 15.5523 7.44772 16 8 16C8.55228 16 9 15.5523 9 15C9 14.4477 8.55228 14 8 14Z"></path>
                <path d="M2.929 1.343C2.54008 1.021 1.96718 1.10898 1.646 1.498C1.32483 1.88681 1.41281 2.45972 1.802 2.781C2.19119 3.10217 2.7641 3.01419 3.08527 2.625C3.40644 2.23618 3.31846 1.66327 2.929 1.343Z"></path>
                <path d="M12.657 11.929C12.338 11.5401 11.765 11.652 11.438 12.041C11.111 12.4299 11.2236 12.9987 11.619 13.326C12.0084 13.6473 12.5813 13.5393 12.908 13.1505C13.2347 12.7616 13.1227 12.1928 12.657 11.929Z"></path>
                <path d="M2 8C1.44772 8 1 8.44772 1 9C1 9.55228 1.44772 10 2 10C2.55228 10 3 9.55228 3 9C3 8.44772 2.55228 8 2 8Z"></path>
                <path d="M14 8C13.4477 8 13 8.44772 13 9C13 9.55228 13.4477 10 14 10C14.5523 10 15 9.55228 15 9C15 8.44772 14.5523 8 14 8Z"></path>
                <path d="M1.343 12.657C1.021 13.046 1.109 13.619 1.498 13.94C1.88681 14.2612 2.45972 14.1732 2.781 13.784C3.10218 13.3952 3.01419 12.8223 2.625 12.5005C2.23618 12.1793 1.66327 12.2673 1.343 12.657Z"></path>
                <path d="M11.929 2.929C11.5401 2.54008 10.9672 2.62807 10.646 3.01688C10.3248 3.40569 10.4128 3.9786 10.802 4.30027C11.1908 4.62144 11.7637 4.53346 12.0854 4.14465C12.4066 3.75583 12.3186 3.1829 11.929 2.929Z"></path>
            </svg>
            <!-- Moon Icon (shown in light mode) -->
            <svg x-show="!$store.theme.isDark" class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.5 2.5C12.1193 2.5 11 3.61929 11 5C11 6.38071 12.1193 7.5 13.5 7.5C14.8807 7.5 16 6.38071 16 5C16 3.61929 14.8807 2.5 13.5 2.5ZM13.5 0C15.9853 0 18 2.01472 18 4.5C18 6.98528 15.9853 9 13.5 9H12.5C12.2239 9 12 9.22386 12 9.5V10C12 14.4183 8.41828 18 4 18C1.23858 18 -0.5 16.2614 -0.5 13.5C-0.5 11.1121 0.995391 9.07629 3.00001 8.29227V8C3.00001 7.72386 3.22387 7.5 3.5 7.5H4.5C8.91828 7.5 12.5 3.91828 12.5 -0.5V-1H13.5Z"></path>
            </svg>
        </button>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-3">
        <!-- Notifications Bell -->
        <div x-data="{ 
            notifOpen: false,
            notifications: [],
            unreadCount: 0,
            init() {
                this.fetchUnreadCount();
                // Refresh every 30 seconds
                setInterval(() => this.fetchUnreadCount(), 30000);
            },
            fetchUnreadCount() {
                fetch('/api/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        this.unreadCount = data.unread_count;
                        if (this.notifOpen) this.fetchLatestUnread();
                    });
            },
            fetchLatestUnread() {
                fetch('/api/notifications/latest-unread')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.data;
                    });
            },
            markAsRead(notification) {
                fetch(`/api/notifications/${notification.id}/mark-as-read`, { method: 'PATCH' })
                    .then(() => {
                        this.fetchUnreadCount();
                    });
            }
        }" class="relative">
            <button @click="notifOpen = !notifOpen; notifOpen && fetchLatestUnread()" class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                <!-- Bell Icon -->
                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.1999 14.9C15.9199 14.7 15.7 14.4 15.7 14V10C15.7 7.9 14.5 6.2 12.9 5.5V5C12.9 3.6 11.8 2.5 10.4 2.5C9 2.5 7.9 3.6 7.9 5V5.5C6.3 6.2 5.1 7.9 5.1 10V14C5.1 14.4 4.9 14.7 4.6 14.9C3.5 15.7 2.8 16.8 2.8 18H16.1C16.1 16.8 15.3 15.7 16.1999 14.9Z"></path>
                    <path d="M11.7 18C11.7 18.6 11.2 19.1 10.5 19.1C9.8 19.1 9.3 18.6 9.3 18H11.7Z"></path>
                </svg>
                <!-- Unread Badge -->
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold"></span>
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="notifOpen" @click.outside="notifOpen = false" class="absolute right-0 mt-2 w-80 rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-dark-800 z-50">
                <!-- Header -->
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</p>
                </div>

                <!-- Notifications List -->
                <div class="max-h-96 overflow-y-auto">
                    <template x-if="notifications.length === 0">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            Belum ada notifikasi baru
                        </div>
                    </template>

                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="border-b border-gray-100 px-4 py-3 hover:bg-gray-50 dark:border-gray-700/50 dark:hover:bg-white/5 cursor-pointer transition" @click="markAsRead(notification)">
                            <div class="flex gap-3">
                                <div class="mt-0.5">
                                    <div class="h-2 w-2 rounded-full bg-brand-500"></div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2" x-text="new Date(notification.created_at).toLocaleString('id-ID')"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-4 py-2 dark:border-gray-700">
                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 py-2">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div x-data="{ userOpen: false }" class="relative">
            <button @click="userOpen = !userOpen" class="flex items-center gap-3 rounded-lg px-3.5 py-2 hover:bg-gray-100 dark:hover:bg-white/5">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-500 text-white font-medium">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                </div>
                <svg class="fill-current text-gray-500 dark:text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 11.75C7.8125 11.75 7.625 11.6875 7.47187 11.5469L2.34375 6.53906C2.03125 6.25 2.03125 5.78906 2.34375 5.5C2.65625 5.21094 3.15625 5.21094 3.46875 5.5L8 9.75L12.5312 5.5C12.8438 5.21094 13.3438 5.21094 13.6562 5.5C13.9688 5.78906 13.9688 6.25 13.6562 6.53906L8.52812 11.5469C8.375 11.6875 8.1875 11.75 8 11.75Z"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userOpen" @click.outside="userOpen = false" class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-dark-800">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 border-t border-gray-200 dark:border-gray-700">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
