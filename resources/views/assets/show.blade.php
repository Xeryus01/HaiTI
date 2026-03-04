<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Asset {{ $asset->asset_code }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('assets.edit', $asset) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    Edit
                </a>
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this asset?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $asset->name }}</h3>
                    <p class="text-gray-600 mb-6">Asset Code: <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">{{ $asset->asset_code }}</code></p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Type</label>
                            <p class="text-gray-800 mt-1 font-medium">{{ $asset->type }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Location</label>
                            <p class="text-gray-800 mt-1">{{ $asset->location }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Specifications</h4>
                        @if($asset->specs && is_array($asset->specs) && count($asset->specs) > 0)
                            <div class="space-y-2">
                                @foreach($asset->specs as $key => $value)
                                    <div class="flex justify-between">
                                        <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                        <span class="text-gray-900 font-medium">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No specifications recorded</p>
                        @endif
                    </div>

                    <hr class="my-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Maintenance History</h4>
                    <div class="space-y-3">
                        @forelse($asset->logs ?? [] as $log)
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $log->action ?? 'Action' }}</p>
                                        <p class="text-sm text-gray-500">{{ optional($log->created_at)->diffForHumans() ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $log->description ?? '' }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No maintenance history</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Asset Status</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Current Status</label>
                            <p class="text-lg font-bold mt-1">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($asset->status === 'ACTIVE')bg-green-100 text-green-700
                                    @elseif($asset->status === 'MAINTENANCE')bg-yellow-100 text-yellow-700
                                    @elseif($asset->status === 'BROKEN')bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $asset->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Acquisition Date</label>
                            <p class="text-gray-800 font-medium mt-1">{{ optional($asset->purchased_at)->format('d/m/Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Created</label>
                            <p class="text-gray-800 font-medium mt-1">{{ $asset->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Last Updated</label>
                            <p class="text-gray-800 font-medium mt-1">{{ $asset->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
