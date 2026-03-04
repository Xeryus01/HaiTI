<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-9">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">{{ __('Profile Settings') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your account information and preferences</p>
        </div>

        <!-- Settings Cards -->
        <div class="space-y-6">
            <!-- Profile Information Card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                <div class="border-b border-gray-200 pb-6 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Profile Information') }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your personal information.</p>
                </div>
                <div class="mt-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                <div class="border-b border-gray-200 pb-6 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Update Password') }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ensure your account has a long, random password to stay secure.</p>
                </div>
                <div class="mt-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="rounded-xl border border-red-200 bg-red-50 p-6 dark:border-red-500/20 dark:bg-red-500/10">
                <div class="border-b border-red-200 pb-6 dark:border-red-500/20">
                    <h2 class="text-lg font-bold text-red-900 dark:text-red-400">{{ __('Delete Account') }}</h2>
                    <p class="mt-1 text-sm text-red-700 dark:text-red-400">Once you delete your account, there is no going back. Please be certain.</p>
                </div>
                <div class="mt-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
