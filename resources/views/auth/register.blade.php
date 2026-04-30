<x-guest-layout>
    <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                {{ __('Full Name') }}
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            @if ($errors->has('name'))
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                {{ __('Email Address') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            @if ($errors->has('email'))
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                {{ __('Password') }}
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            @if ($errors->has('password'))
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20" />
            @if ($errors->has('password_confirmation'))
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        <!-- Register Button & Login Link -->
        <div class="flex items-center justify-between pt-2">
            <a href="{{ url()->to(route('login')) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                {{ __('Already have an account?') }}
            </a>

            <button id="registerBtn" type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-6 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                <span id="registerBtnText">{{ __('Create Account') }}</span>
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
            btnText.textContent = '{{ __('Creating Account...') }}';
            btnSpinner.classList.remove('hidden');
        });
    </script>
</x-guest-layout>
