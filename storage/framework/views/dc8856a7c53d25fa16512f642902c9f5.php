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
            loading: false,
            error: null,
            csrfToken: '<?php echo e(csrf_token()); ?>',
            init() {
                this.requestNotificationPermission();
                this.fetchUnreadCount();
                setInterval(() => this.fetchUnreadCount(), 30000);
            },
            requestNotificationPermission() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
            },
            fetchUnreadCount() {
                const previousCount = this.unreadCount;
                fetch('/api/notifications/unread-count', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        const newCount = data.unread_count || 0;
                        if (newCount > previousCount && previousCount > 0) {
                            this.showNewNotificationAlert();
                        }
                        this.unreadCount = newCount;
                        this.error = null;
                        if (this.notifOpen) this.fetchLatestUnread();
                    })
                    .catch(error => {
                        console.error('Error fetching unread count:', error);
                        this.error = 'Failed to load notifications';
                    });
            },
            fetchLatestUnread() {
                this.loading = true;
                this.error = null;
                fetch('/api/notifications/latest-unread', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        this.notifications = data.data || [];
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching latest unread:', error);
                        this.loading = false;
                        this.error = 'Failed to load notifications';
                    });
            },
            markAsRead(notification) {
                fetch(`/api/notifications/${notification.id}/mark-as-read`, {
                    method: 'PATCH',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(() => {
                        this.fetchUnreadCount();
                    })
                    .catch(error => {
                        console.error('Error marking as read:', error);
                    });
            },
            showNewNotificationAlert() {
                const bell = this.$el.querySelector('button');
                bell.classList.add('animate-pulse');
                setTimeout(() => {
                    bell.classList.remove('animate-pulse');
                }, 2000);

                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('TimCare - Notifikasi Baru', {
                        body: 'Anda memiliki notifikasi baru',
                        icon: '/favicon.ico'
                    });
                }
            }
        }" class="relative">
            <button @click="notifOpen = !notifOpen; notifOpen && fetchLatestUnread()" class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                <!-- Bell Icon -->
                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.1999 14.9C15.9199 14.7 15.7 14.4 15.7 14V10C15.7 7.9 14.5 6.2 12.9 5.5V5C12.9 3.6 11.8 2.5 10.4 2.5C9 2.5 7.9 3.6 7.9 5V5.5C6.3 6.2 5.1 7.9 5.1 10V14C5.1 14.4 4.9 14.7 4.6 14.9C3.5 15.7 2.8 16.8 2.8 18H16.1C16.1 16.8 15.3 15.7 16.1999 14.9Z"></path>
                    <path d="M11.7 18C11.7 18.6 11.2 19.1 10.5 19.1C9.8 19.1 9.3 18.6 9.3 18H11.7Z"></path>
                </svg>
                <!-- Unread Badge -->
                <span x-show="unreadCount > 0" x-text="unreadCount" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75" class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold"></span>
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-80 sm:w-80 md:w-96 rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-dark-800 z-60 max-h-[80vh] overflow-hidden">
                <!-- Header -->
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</p>
                </div>

                <!-- Notifications List -->
                <div class="max-h-96 overflow-y-auto">
                    <!-- Loading State -->
                    <template x-if="loading">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-brand-500 mx-auto mb-2"></div>
                            Memuat notifikasi...
                        </div>
                    </template>

                    <!-- Error State -->
                    <template x-if="error && !loading">
                        <div class="px-4 py-8 text-center text-sm text-red-500 dark:text-red-400">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span x-text="error"></span>
                            <br>
                            <button @click="fetchLatestUnread()" class="text-xs underline hover:no-underline mt-1">
                                Coba lagi
                            </button>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="!loading && !error && notifications.length === 0">
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            Belum ada notifikasi baru
                        </div>
                    </template>

                    <!-- Notifications -->
                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="border-b border-gray-100 px-4 py-3 hover:bg-gray-50 dark:border-gray-700/50 dark:hover:bg-white/5 cursor-pointer transition duration-150 ease-in-out" @click="markAsRead(notification)">
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex-shrink-0">
                                    <!-- Type-based Icon -->
                                    <div x-show="notification.type === 'ticket_comment'" class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div x-show="notification.type === 'ticket_status'" class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div x-show="notification.type !== 'ticket_comment' && notification.type !== 'ticket_status'" class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="notification.title"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 break-words" x-text="notification.message"></p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2" x-text="new Date(notification.created_at).toLocaleString('id-ID', {
                                        day: 'numeric',
                                        month: 'short',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-4 py-2 dark:border-gray-700">
                    <a href="<?php echo e(route('notifications.index')); ?>" class="block text-center text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 py-2">
                        Lihat semua notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div x-data="{ userOpen: false }" class="relative">
            <button @click="userOpen = !userOpen" class="flex items-center gap-3 rounded-lg px-3.5 py-2 hover:bg-gray-100 dark:hover:bg-white/5">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-500 text-white font-medium">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <div class="hidden text-left sm:block">
                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(auth()->user()->email); ?></p>
                </div>
                <svg class="fill-current text-gray-500 dark:text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 11.75C7.8125 11.75 7.625 11.6875 7.47187 11.5469L2.34375 6.53906C2.03125 6.25 2.03125 5.78906 2.34375 5.5C2.65625 5.21094 3.15625 5.21094 3.46875 5.5L8 9.75L12.5312 5.5C12.8438 5.21094 13.3438 5.21094 13.6562 5.5C13.9688 5.78906 13.9688 6.25 13.6562 6.53906L8.52812 11.5469C8.375 11.6875 8.1875 11.75 8 11.75Z"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="userOpen" @click.outside="userOpen = false" class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-dark-800">
                <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">Profil Saya</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5 border-t border-gray-200 dark:border-gray-700">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views/layouts/header.blade.php ENDPATH**/ ?>