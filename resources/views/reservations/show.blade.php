<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reservation {{ $reservation->code }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('reservations.edit', $reservation) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    Edit
                </a>
                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this reservation?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-door-open text-blue-600 mr-2"></i>{{ $reservation->room_name }}
                    </h3>
                    <p class="text-gray-600 mb-4">Reservation Code: <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">{{ $reservation->code }}</code></p>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">Purpose</h4>
                        <p class="text-blue-800">{{ $reservation->purpose }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-semibold text-gray-600 block mb-2">Start Time</label>
                            <p class="text-lg font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-semibold text-gray-600 block mb-2">End Time</label>
                            <p class="text-lg font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Duration</h4>
                        <p class="text-lg font-bold text-gray-800">
                            @php
                                $start = \Carbon\Carbon::parse($reservation->start_time);
                                $end = \Carbon\Carbon::parse($reservation->end_time);
                                $hours = $end->diffInHours($start);
                                $minutes = $end->diffInMinutes($start) % 60;
                            @endphp
                            {{ $hours }}h {{ $minutes }}m
                        </p>
                    </div>

                    <hr class="my-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Reservation Details</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600 font-medium">Organized By:</span>
                            <span class="text-gray-800">{{ optional($reservation->organizer)->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600 font-medium">Created:</span>
                            <span class="text-gray-800">{{ $reservation->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 font-medium">Last Updated:</span>
                            <span class="text-gray-800">{{ $reservation->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Status</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Current Status</label>
                            <p class="text-lg font-bold mt-1">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($reservation->status === 'PENDING')bg-yellow-100 text-yellow-700
                                    @elseif($reservation->status === 'CONFIRMED')bg-green-100 text-green-700
                                    @elseif($reservation->status === 'CANCELLED')bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $reservation->status }}
                                </span>
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-600 mb-1">Reservation Code</p>
                            <p class="font-mono text-sm font-bold text-gray-800">{{ $reservation->code }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>