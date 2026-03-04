<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">Edit Reservation {{ $reservation->code }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update reservation details</p>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800 sm:p-8">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 p-4 dark:bg-red-500/10">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="font-medium text-red-800 dark:text-red-400">Please correct the following errors:</h3>
                            <ul class="mt-2 list-inside space-y-1 text-sm text-red-700 dark:text-red-400">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6" id="reservationForm">
                @csrf
                @method('PATCH')

                <!-- Room Name -->
                <div>
                    <label for="room_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Room Name
                    </label>
                    <input id="room_name" type="text" name="room_name" value="{{ old('room_name', $reservation->room_name) }}" required placeholder="e.g., Conference Room A" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20 @error('room_name') border-red-500 @enderror" />
                    @error('room_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Purpose
                    </label>
                    <textarea id="purpose" name="purpose" rows="3" required placeholder="What is this reservation for?" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20 @error('purpose') border-red-500 @enderror">{{ old('purpose', $reservation->purpose) }}</textarea>
                    @error('purpose')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time Grid -->
                <div class="grid gap-6 sm:grid-cols-2">
                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Start Time
                        </label>
                        <input id="start_time" type="datetime-local" name="start_time_local" value="{{ old('start_time_local', $reservation->start_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:focus:border-brand-600 dark:focus:ring-brand-900/20 @error('start_time') border-red-500 @enderror" />
                        <input type="hidden" name="start_time" id="start_time_hidden" />
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            End Time
                        </label>
                        <input id="end_time" type="datetime-local" name="end_time_local" value="{{ old('end_time_local', $reservation->end_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 disabled:cursor-default disabled:bg-gray-50 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:focus:border-brand-600 dark:focus:ring-brand-900/20 @error('end_time') border-red-500 @enderror" />
                        <input type="hidden" name="end_time" id="end_time_hidden" />
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Status
                    </label>
                    <select id="status" name="status" class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:focus:border-brand-600 dark:focus:ring-brand-900/20">
                        @foreach(['PENDING','APPROVED','REJECTED','COMPLETED','CANCELLED'] as $s)
                            <option value="{{ $s }}" {{ $reservation->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6">
                    <a href="{{ route('reservations.index') }}" class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-center font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 rounded-lg bg-brand-600 px-4 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    // Convert datetime-local format (YYYY-MM-DDTHH:mm) to Y-m-d H:i format
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
</x-app-layout>
