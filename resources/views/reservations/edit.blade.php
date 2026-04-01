<x-app-layout>
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Perbarui Pengajuan Zoom</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gunakan halaman ini untuk memperbaiki jadwal atau menambahkan hasil follow up.</p>
        </div>

        <div class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800 sm:p-8">
            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6" id="reservationForm">
                @csrf
                @method('PATCH')

                <div>
                    <label for="room_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Kegiatan / Ruang</label>
                    <input id="room_name" type="text" name="room_name" value="{{ old('room_name', $reservation->room_name) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                </div>

                <div>
                    <label for="purpose" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                    <textarea id="purpose" name="purpose" rows="3" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white">{{ old('purpose', $reservation->purpose) }}</textarea>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="start_time" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Mulai</label>
                        <input id="start_time" type="datetime-local" name="start_time_local" value="{{ old('start_time_local', $reservation->start_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                        <input type="hidden" name="start_time" id="start_time_hidden" />
                    </div>
                    <div>
                        <label for="end_time" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Selesai</label>
                        <input id="end_time" type="datetime-local" name="end_time_local" value="{{ old('end_time_local', $reservation->end_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white" />
                        <input type="hidden" name="end_time" id="end_time_hidden" />
                    </div>
                </div>

                @if(auth()->user()->hasPermissionTo('approve reservations'))
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
                        <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 dark:border-gray-600 dark:bg-dark-800 dark:text-white">{{ old('notes', $reservation->notes) }}</textarea>
                    </div>
                @endif

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('reservations.show', $reservation) }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Batal</a>
                    <button type="submit" class="flex-1 rounded-lg bg-brand-600 px-4 py-2.5 text-center font-medium text-white hover:bg-brand-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('reservationForm').addEventListener('submit', function () {
    const startTimeLocal = document.getElementById('start_time').value;
    const endTimeLocal = document.getElementById('end_time').value;

    if (startTimeLocal) {
        const [date, time] = startTimeLocal.split('T');
        document.getElementById('start_time_hidden').value = date + ' ' + time;
    }

    if (endTimeLocal) {
        const [date, time] = endTimeLocal.split('T');
        document.getElementById('end_time_hidden').value = date + ' ' + time;
    }
});
</script>
</x-app-layout>
