@extends('layouts.app')

@section('title', 'Places Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Places Management') }}</h1>
        <a href="{{ route('places.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            {{ __('Add New Place') }}
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Search Places') }}</label>
                <input type="text"
                       id="search"
                       placeholder="{{ __('Search by name or description...') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Category') }}</label>
                <select id="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Nearby Search -->
            <div>
                <label for="nearby" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Find Nearby') }}</label>
                <button id="nearby-btn"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md font-medium">
                    {{ __('Use My Location') }}
                </button>
            </div>
        </div>

        <!-- Search Results Info -->
        <div id="search-results" class="mt-4 hidden">
            <p class="text-sm text-gray-600">{{ __('Found') }} <span id="results-count">0</span> {{ __('places') }}</p>
        </div>
    </div>

    <!-- Places Grid -->
    <div id="places-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($places as $place)
            <div class="bg-white rounded-lg shadow-md overflow-hidden place-card"
                 data-place-id="{{ $place->id }}"
                 data-category-id="{{ $place->category_id }}">
                @if($place->category)
                    <div class="px-4 py-2 text-xs font-semibold text-white bg-blue-500">
                        {{ $place->category }}
                    </div>
                @endif
                
                @if($place->image)
                    <div class="h-48 w-full overflow-hidden">
                        <img src="{{ asset('storage/' . $place->image) }}" alt="{{ $place->name }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $place->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $place->description }}</p>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $place->address }}
                        </p>
                    </div>

                    @if($place->latitude && $place->longitude)
                        <p class="text-xs text-gray-400 mb-3">
                            Lat: {{ number_format($place->latitude, 6) }},
                            Lng: {{ number_format($place->longitude, 6) }}
                        </p>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm {{ $place->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $place->is_active ? __('Active') : __('Inactive') }}
                        </span>

                        <div class="flex space-x-2">
                            <a href="{{ route('places.show', $place->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">{{ __('View') }}</a>
                            <a href="{{ route('places.edit', $place->id) }}"
                               class="text-green-600 hover:text-green-800 text-sm font-medium">{{ __('Edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No places found') }}</h3>
                <p class="text-gray-500">{{ __('Get started by creating your first place.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($places instanceof \Illuminate\Pagination\LengthAwarePaginator && $places->hasPages())
        <div class="mt-8">
            {{ $places->links() }}
        </div>
    @endif
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
                        {{ __('Delete') }}
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
                document.querySelector(`[data-place-id="${placeToDelete}"]`).remove();
                closeDeleteModal();
                showNotification('Place deleted successfully', 'success');
            } else {
                showNotification('Failed to delete place', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting the place', 'error');
        });
    }
});

function showNotification(message, type) {
    // Simple notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Search functionality
document.getElementById('search').addEventListener('input', function(e) {
    const query = e.target.value.trim();

    if (query.length > 2) {
        searchPlaces(query);
    } else if (query.length === 0) {
        loadPlaces();
    }
});

function searchPlaces(query) {
    fetch(`{{ route('places.search') }}?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            updatePlacesDisplay(data.places);
            document.getElementById('search-results').classList.remove('hidden');
            document.getElementById('results-count').textContent = data.places.length;
        })
        .catch(error => {
            console.error('Search error:', error);
        });
}

function loadPlaces() {
    fetch('{{ route('places.index') }}')
        .then(response => response.text())
        .then(html => {
            // This is a simplified approach - in a real app, you'd want to return JSON
            location.reload();
        });
}

function updatePlacesDisplay(places) {
    const container = document.getElementById('places-container');
    // This would need more sophisticated handling in a real application
    // For now, we'll just show the count
}
</script>
@endpush