<x-app-layout>
<div class="min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Detail User</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Informasi lengkap user {{ $user->name }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url()->to(route('users.index')) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-gray-300 dark:hover:bg-dark-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="mx-auto max-w-2xl">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800">
                <div class="border-b border-gray-200 p-5 sm:p-7.5 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Pengguna</h2>
                </div>

                <!-- User Details -->
                <div class="space-y-4 p-5 sm:p-7.5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Handphone</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->phone_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                            <div class="mt-1">
                                @if($user->hasRole('Admin'))
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Admin</span>
                                @elseif($user->hasRole('Teknisi'))
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">Teknisi</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-200">User</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dibuat Pada</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terakhir Diperbarui</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 p-5 sm:p-7.5 dark:border-gray-700 dark:bg-dark-700/50">
                    <a href="{{ url()->to(route('users.editPassword', $user)) }}" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 font-medium text-white hover:bg-amber-700 dark:hover:bg-amber-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Ubah Password
                    </a>
                    <a href="{{ url()->to(route('users.edit', $user)) }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>