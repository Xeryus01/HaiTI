<x-app-layout>
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Edit Pengajuan Zoom</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ubah detail pengajuan Zoom Anda.</p>
        </div>

        <div class="max-w-3xl">
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800 sm:p-8">
                @if($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="room_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Kegiatan / Ruang</label>
                        <input id="room_name" type="text" name="room_name" value="{{ old('room_name', $reservation->room_name) }}" placeholder="Contoh: Workshop Laravel 2026" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('room_name') border-red-500 @enderror" />
                        @error('room_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="purpose" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                        <textarea id="purpose" name="purpose" rows="3" placeholder="Jelaskan tujuan pengajuan zoom ini..." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('purpose') border-red-500 @enderror">{{ old('purpose', $reservation->purpose) }}</textarea>
                        @error('purpose')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="start_time_local" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Waktu Mulai</label>
                            <input id="start_time_local" type="datetime-local" name="start_time_local" value="{{ old('start_time_local', $reservation->start_time->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('start_time_local') border-red-500 @enderror" />
                            @error('start_time_local')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time_local" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Waktu Selesai</label>
                            <input id="end_time_local" type="datetime-local" name="end_time_local" value="{{ old('end_time_local', $reservation->end_time->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('end_time_local') border-red-500 @enderror" />
                            @error('end_time_local')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 pt-6">
                        <a href="{{ route('reservations.show', $reservation) }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Batal</a>
                        <button type="submit" class="flex-1 rounded-lg bg-brand-600 px-4 py-2.5 text-center font-medium text-white hover:bg-brand-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
