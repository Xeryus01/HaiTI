<x-app-layout>
<div class="min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Ajukan Ruang Zoom</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi jadwal dan keperluan meeting. Admin atau teknisi akan menindaklanjuti dan menambahkan link Zoom.</p>
        </div>

        <div class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800 sm:p-8">
            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                    <p class="font-medium">Mohon periksa kembali data berikut:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reservations.store') }}" enctype="multipart/form-data" class="space-y-6" id="reservationForm">
                @csrf

                <div>
                    <label for="room_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Kegiatan / Ruang</label>
                    <input id="room_name" type="text" name="room_name" value="{{ old('room_name') }}" required placeholder="Contoh: Rapat Koordinasi Mingguan" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('room_name') border-red-500 @enderror" />
                </div>

                <div>
                    <label for="purpose" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Keperluan</label>
                    <textarea id="purpose" name="purpose" rows="3" required placeholder="Contoh: Meeting internal divisi, presentasi, atau koordinasi proyek" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('purpose') border-red-500 @enderror">{{ old('purpose') }}</textarea>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="start_time" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Mulai</label>
                        <input id="start_time" type="datetime-local" name="start_time_local" required
                            @if(!auth()->user()->hasRole('Admin'))
                                min="{{ date('Y-m-d\TH:i') }}"
                            @endif
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('start_time') border-red-500 @enderror" />
                    </div>
                    <div>
                        <label for="end_time" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Selesai</label>
                        <input id="end_time" type="datetime-local" name="end_time_local" required
                            @if(!auth()->user()->hasRole('Admin'))
                                min="{{ date('Y-m-d\TH:i') }}"
                            @endif
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 dark:border-gray-600 dark:bg-dark-800 dark:text-white @error('end_time') border-red-500 @enderror" />
                    </div>
                <div>
                    <label for="nota_dinas" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nota Dinas <span class="text-red-500">*</span></label>
                    <input id="nota_dinas" type="file" name="nota_dinas" accept=".pdf" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-700 dark:border-gray-600 dark:bg-dark-800 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-700 dark:file:bg-brand-500/10 dark:file:text-brand-300 @error('nota_dinas') border-red-500 @enderror" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload nota dinas dalam format PDF (maksimal 5MB)</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('reservations.index') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">Batal</a>
                    <button type="submit" class="flex-1 rounded-lg bg-brand-600 px-4 py-2.5 text-center font-medium text-white hover:bg-brand-700">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
