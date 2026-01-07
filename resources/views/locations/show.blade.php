@extends('layouts.app')

@section('title', $place->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('places.index') }}" class="text-gray-700 hover:text-blue-600">
                        {{ __('Places Management') }}
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-gray-500">{{ $place->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Category Badge -->
                @if($place->category)
                    <div class="px-6 py-3 text-sm font-semibold text-white bg-blue-500">
                        {{ $place->category}}
                    </div>
                @endif

                <!-- Place Image -->
                @if($place->image ?? false)
                    <div class="aspect-video bg-gray-200">
                        <img src="{{ asset('storage/' . $place->image) }}"
                             alt="{{ $place->name }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6">
                    <!-- Place Name and Status -->
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $place->name }}</h1>
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $place->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $place->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Description') }}</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $place->description }}</p>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Address') }}</h3>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">{{ $place->address }}</span>
                        </div>
                    </div>

                    <!-- Coordinates -->
                    @if($place->latitude && $place->longitude)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Coordinates') }}</h3>
                            <div class="bg-gray-50 rounded-md p-3 font-mono text-sm">
                                <div>{{ __('Latitude') }}: {{ number_format($place->latitude, 8) }}</div>
                                <div>{{ __('Longitude') }}: {{ number_format($place->longitude, 8) }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="text-sm text-gray-500 border-t pt-4">
                        <div class="flex justify-between">
                            <span>{{ __('Created') }}: {{ optional($place->created_at)->format('M j, Y \a\t g:i A') ?? 'N/A' }}</span>
                            <span>{{ __('Last Updated') }}: {{ optional($place->updated_at)->format('M j, Y \a\t g:i A') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Actions') }}</h3>

                <div class="space-y-3">
                    <a href="{{ route('places.edit', $place->id) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        {{ __('Edit Place') }}
                    </a>

                    <button onclick="deletePlace({{ $place->id }})"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                            <path fill-rule="evenodd" d="M10 5a2 2 0 00-2 2v6a2 2 0 004 0V7a2 2 0 00-2-2zm-4 4a4 4 0 118 0v4a4 4 0 01-8 0V9z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Delete Place') }}
                    </button>

                    <a href="{{ route('places.index') }}"
                       class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium text-center block">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Back to Places') }}
                    </a>
                </div>
            </div>

            <!-- Map Card (if coordinates available) -->
            @if($place->latitude && $place->longitude)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Location') }}</h3>
                    <div class="aspect-square bg-gray-200 rounded-md mb-3">
                        <!-- Placeholder for map - in a real app, you'd integrate with Google Maps or similar -->
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm">{{ __('Map View') }}</p>
                                <p class="text-xs">{{ number_format($place->latitude, 4) }}, {{ number_format($place->longitude, 4) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <button onclick="openInMaps()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('Open in Maps') }}
                        </button>

                        <button onclick="getDirections()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('Get Directions') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Confirm Deletion') }}</h3>
                <p class="text-gray-600 mb-6">{{ __('Are you sure you want to delete this place?') }}</p>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </button>
                    <button id="confirm-delete-btn"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        {{ __('Delete Place') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let placeToDelete = null;

function deletePlace(placeId) {
    placeToDelete = placeId;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    placeToDelete = null;
    document.getElementById('delete-modal').classList.add('hidden');
}

document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    if (placeToDelete) {
        fetch(`{{ route('places.index') }}/${placeToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('places.index') }}';
            } else {
                showNotification('Failed to delete place', 'error');
                closeDeleteModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the place', 'error');
            closeDeleteModal();
        });
    }
});

function openInMaps() {
    const lat = {{ $place->latitude ?? 0 }};
    const lng = {{ $place->longitude ?? 0 }};
    const address = encodeURIComponent('{{ addslashes($place->address) }}');

    // Try to open in native maps app, fallback to Google Maps
    if (navigator.userAgent.match(/(iPhone|iPod|iPad)/i)) {
        window.location.href = `maps:///?q=${address}&ll=${lat},${lng}`;
    } else if (navigator.userAgent.match(/Android/i)) {
        window.location.href = `geo:${lat},${lng}?q=${lat},${lng}(${encodeURIComponent('{{ $place->name }}')})`;
    } else {
        window.open(`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`, '_blank');
    }
}

function getDirections() {
    const lat = {{ $place->latitude ?? 0 }};
    const lng = {{ $place->longitude ?? 0 }};

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                if (navigator.userAgent.match(/(iPhone|iPod|iPad)/i)) {
                    window.location.href = `maps:///?saddr=${userLat},${userLng}&daddr=${lat},${lng}&dirflg=d`;
                } else if (navigator.userAgent.match(/Android/i)) {
                    window.location.href = `geo:${userLat},${userLng}?q=${lat},${lng}`;
                } else {
                    window.open(`https://www.google.com/maps/dir/${userLat},${userLng}/${lat},${lng}`, '_blank');
                }
            },
            function(error) {
                showNotification('Please enable location services to get directions.', 'error');
            }
        );
    } else {
        showNotification('Geolocation is not supported by this browser.', 'error');
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush