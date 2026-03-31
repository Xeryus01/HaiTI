<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'ITSM Dashboard')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php if(auth()->guard()->check()): ?>
        <script>window.Auth = { user: <?php echo json_encode(auth()->user()); ?> };</script>
    <?php endif; ?>
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
</head>
<body class="h-full bg-gray-50 dark:bg-dark-900" x-data x-init="$store.theme.init()">
    <div class="flex h-full bg-gray-50 dark:bg-dark-900">
        <!-- Sidebar -->
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Top Header -->
            <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                    <div class="mx-5 mt-5 sm:mx-6 lg:mx-8">
                        <div class="rounded-xl border border-green-500 bg-green-50 p-4 dark:border-green-500/30 dark:bg-green-500/15">
                            <div class="flex items-start gap-3">
                                <div class="text-green-500">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18.75C14.9706 18.75 19 14.7206 19 9.75C19 4.77944 14.9706 0.75 10 0.75C5.02944 0.75 1 4.77944 1 9.75C1 14.7206 5.02944 18.75 10 18.75ZM14.7813 7.03125C15.1328 6.65625 15.1328 6.09375 14.7813 5.71875C14.4375 5.33125 13.8438 5.33125 13.5 5.71875L8.75 10.5938L6.5 8.33125C6.15625 7.9375 5.5625 7.9375 5.21875 8.33125C4.875 8.70625 4.875 9.26875 5.21875 9.64375L7.9375 12.375C8.28125 12.75 8.875 12.75 9.21875 12.375L14.7813 7.03125Z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-green-600 dark:text-green-500"><?php echo e(session('success')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mx-5 mt-5 sm:mx-6 lg:mx-8">
                        <div class="rounded-xl border border-red-500 bg-red-50 p-4 dark:border-red-500/30 dark:bg-red-500/15">
                            <div class="flex items-start gap-3">
                                <div class="text-red-500">
                                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18.75C14.9706 18.75 19 14.7206 19 9.75C19 4.77944 14.9706 0.75 10 0.75C5.02944 0.75 1 4.77944 1 9.75C1 14.7206 5.02944 18.75 10 18.75ZM10.75 14.25C10.75 14.6642 10.4142 15 10 15C9.5858 15 9.25 14.6642 9.25 14.25C9.25 13.8358 9.5858 13.5 10 13.5C10.4142 13.5 10.75 13.8358 10.75 14.25ZM9.25 6.75C9.25 6.3358 9.5858 6 10 6C10.4142 6 10.75 6.3358 10.75 6.75V11.25C10.75 11.6642 10.4142 12 10 12C9.5858 12 9.25 11.6642 9.25 11.25V6.75Z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-red-600 dark:text-red-500"><?php echo e(session('error')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php echo e($slot); ?>

            </main>
        </div>
    </div>

    <!-- Alpine.js Theme Toggle (uncomment if needed) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\BPS 1900\Documents\HaiTI\resources\views\layouts\app.blade.php ENDPATH**/ ?>