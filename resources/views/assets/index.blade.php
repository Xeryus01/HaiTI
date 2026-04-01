<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Aset TI</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pantau dan kelola semua aset TI organisasi</p>
            </div>
            @can('manage assets')
                <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 font-medium text-white hover:bg-brand-700">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 3C10.5523 3 11 3.44772 11 4V9H16C16.5523 9 17 9.44772 17 10C17 10.5523 16.5523 11 16 11H11V16C11 16.5523 10.5523 17 10 17C9.44772 17 9 16.5523 9 16V11H4C3.44772 11 3 10.5523 3 10C3 9.44772 3.44772 9 4 9H9V4C9 3.44772 9.44772 3 10 3Z"></path>
                    </svg>
                    Tambah Aset
                </a>
            @endcan
        </div>

        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-dark-800">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Import Aset dari Excel</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unduh template lalu unggah file Excel dengan kolom yang sesuai.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('assets.template') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-gray-300 dark:hover:bg-white/5">Download Template Excel</a>
                </div>
            </div>
            <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-wrap items-end gap-3">
                @csrf
                <div>
                    <label for="file" class="text-sm font-medium text-gray-900 dark:text-white">Pilih File (xlsx/csv)</label>
                    <input id="file" name="file" type="file" accept=".xlsx,.xls,.csv" required class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                    @error('file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">Upload Excel</button>
            </form>
        </div>

        <!-- Assets Table -->
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-dark-800 overflow-hidden">
            <!-- Table Header -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-white/5">
                        <tr>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Kode</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Nama</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Tipe</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Kondisi</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Lokasi</span>
                            </th>
                            <th class="px-5 py-3.5 text-left sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Pemegang</span>
                            </th>
                            <th class="px-5 py-3.5 text-right sm:px-6">
                                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Aksi</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-semibold text-brand-600 dark:text-brand-400">{{ $asset->asset_code }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $asset->name }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->type }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @if($asset->status === 'ACTIVE') bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        @elseif($asset->status === 'MAINTENANCE') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        @elseif(in_array($asset->status, ['SOLD', 'RETIRED', 'INACTIVE'], true)) bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400
                                        @else bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        @endif">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @if($asset->condition === 'GOOD') bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                                        @elseif($asset->condition === 'FAIR') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                                        @elseif($asset->condition === 'POOR') bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400
                                        @endif">
                                        {{ ucfirst(strtolower($asset->condition ?? 'N/A')) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->location ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->holder ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4 text-right sm:px-6">
                                    <a href="{{ route('assets.show', $asset) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center sm:px-6">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aset</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($assets->hasPages())
                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700 sm:px-6">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
