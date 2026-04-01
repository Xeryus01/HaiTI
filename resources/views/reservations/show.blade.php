<x-app-layout>
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">{{ $reservation->room_name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $reservation->code }} • {{ $reservation->status_label }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reservations.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Kembali</a>
                @if(auth()->user()->hasAnyRole(['Admin', 'Teknisi']) || auth()->id() === $reservation->requester_id)
                    <a href="{{ route('reservations.edit', $reservation) }}" class="rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700">Ubah</a>
                @endif
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Detail Pengajuan</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reservation->purpose }}</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu mulai</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $reservation->start_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waktu selesai</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $reservation->end_time->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Nota Dinas</h2>
                    @if($reservation->nota_dinas_path)
                        <div class="space-y-4">
                            <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Nota Dinas Pengajuan</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">File PDF yang diunggah saat pengajuan</p>
                                    </div>
                                    <a href="{{ route('reservations.nota-dinas', $reservation) }}" target="_blank" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Buka PDF</a>
                                </div>

                                <iframe src="{{ route('reservations.nota-dinas', $reservation) }}" title="Nota Dinas" class="h-96 w-full rounded-lg border border-gray-200 dark:border-gray-700"></iframe>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nota dinas belum diunggah.</p>
                    @endif
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Hasil Follow Up</h2>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Link Zoom</p>
                            @if($reservation->zoom_link)
                                <a href="{{ $reservation->zoom_link }}" target="_blank" class="font-medium text-brand-600 hover:underline">{{ $reservation->zoom_link }}</a>
                            @else
                                <p class="text-gray-700 dark:text-gray-300">Belum ditambahkan.</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Catatan tindak lanjut</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ $reservation->notes ?: 'Belum ada catatan dari petugas.' }}</p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasPermissionTo('approve reservations'))
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Tindak Lanjut oleh Teknisi / Admin</h2>
                        <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-4" id="followUpForm">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white">
                                    @foreach(\App\Models\Reservation::statusLabels() as $value => $label)
                                        <option value="{{ $value }}" {{ $reservation->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="zoom_link" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Link Zoom</label>
                                <input id="zoom_link" type="url" name="zoom_link" value="{{ old('zoom_link', $reservation->zoom_link) }}" placeholder="https://zoom.us/j/..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                            </div>
                            <div>
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Catatan tindak lanjut</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Link aktif 10 menit sebelum mulai." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white">{{ old('notes', $reservation->notes) }}</textarea>
                            </div>
                            <button type="submit" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-700">Simpan Follow Up</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-3 text-sm font-bold uppercase text-gray-500 dark:text-gray-400">Ringkasan</h3>
                    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Pemohon</p>
                            <p>{{ optional($reservation->requester)->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Ditangani oleh</p>
                            <p>{{ optional($reservation->approver)->name ?? 'Belum ada petugas' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Dibuat pada</p>
                            <p>{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                            <p>{{ $reservation->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>