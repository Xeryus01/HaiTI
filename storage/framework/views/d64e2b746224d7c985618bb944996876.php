<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <form id="registerForm" method="POST" action="https://digistat.web.bps.go.id/timcare/register" class="space-y-6">
        <?php echo csrf_field(); ?>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                <?php echo e(__('Full Name')); ?>

            </label>
            <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus autocomplete="name" placeholder="John Doe" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            <?php if($errors->has('name')): ?>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($errors->first('name')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                <?php echo e(__('Email Address')); ?>

            </label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username" placeholder="name@example.com" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            <?php if($errors->has('email')): ?>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($errors->first('email')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                <?php echo e(__('Password')); ?>

            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            <?php if($errors->has('password')): ?>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($errors->first('password')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                <?php echo e(__('Confirm Password')); ?>

            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            <?php if($errors->has('password_confirmation')): ?>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($errors->first('password_confirmation')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Register Button & Login Link -->
        <div class="flex items-center justify-between pt-2">
            <a href="<?php echo e(url()->to(route('login'))); ?>" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                <?php echo e(__('Already have an account?')); ?>

            </a>

            <button id="registerBtn" type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-6 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                <span id="registerBtnText"><?php echo e(__('Create Account')); ?></span>
                <span id="registerBtnSpinner" class="hidden ml-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('registerBtn');
            const btnText = document.getElementById('registerBtnText');
            const btnSpinner = document.getElementById('registerBtnSpinner');

            btn.disabled = true;
            btnText.textContent = '<?php echo e(__('Creating Account...')); ?>';
            btnSpinner.classList.remove('hidden');
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH /home/digistat/timcare/resources/views/auth/register.blade.php ENDPATH**/ ?>