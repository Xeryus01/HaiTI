<x-app-layout>
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Edit Jadwal Piket</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $monthNames[$schedule->month] }} {{ $schedule->year }}</p>
            </div>
            <a href="{{ route('piket.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">
                ← Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-400 bg-red-100 p-4 text-red-700 dark:border-red-500/30 dark:bg-red-500/15 dark:text-red-400">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-dark-800">
            <form action="{{ route('piket.update', $schedule->month) }}" method="POST" class="p-5 sm:p-7.5 lg:p-9">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Lantai 1 -->
                    <div>
                        <label for="lantai_1" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Lantai 1</label>
                        <select id="lantai_1" name="lantai_1" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $name)
                                <option value="{{ $name }}" {{ $schedule->lantai_1 === $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lantai 2 -->
                    <div>
                        <label for="lantai_2" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Lantai 2</label>
                        <select id="lantai_2" name="lantai_2" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $name)
                                <option value="{{ $name }}" {{ $schedule->lantai_2 === $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TU -->
                    <div>
                        <label for="tu" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">TU</label>
                        <select id="tu" name="tu" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $name)
                                <option value="{{ $name }}" {{ $schedule->tu === $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Current Schedule Preview -->
                    <div class="rounded-lg border border-brand-200 bg-brand-50 p-4 dark:border-brand-500/30 dark:bg-brand-500/15">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Preview</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Lantai 1:</span>
                                <span class="font-semibold text-gray-900 dark:text-white" id="preview_lantai_1">{{ $schedule->lantai_1 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Lantai 2:</span>
                                <span class="font-semibold text-gray-900 dark:text-white" id="preview_lantai_2">{{ $schedule->lantai_2 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">TU:</span>
                                <span class="font-semibold text-gray-900 dark:text-white" id="preview_tu">{{ $schedule->tu }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-brand-600 px-6 py-2.5 font-medium text-white hover:bg-brand-700 transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('piket.index') }}" class="inline-flex flex-1 items-center justify-center rounded-lg border border-gray-300 px-6 py-2.5 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Update preview in real-time
    document.getElementById('lantai_1').addEventListener('change', function() {
        document.getElementById('preview_lantai_1').textContent = this.value || '-';
    });
    document.getElementById('lantai_2').addEventListener('change', function() {
        document.getElementById('preview_lantai_2').textContent = this.value || '-';
    });
    document.getElementById('tu').addEventListener('change', function() {
        document.getElementById('preview_tu').textContent = this.value || '-';
    });
</script>
</x-app-layout>
