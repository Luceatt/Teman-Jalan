@extends('layouts.app')

@section('title', 'Edit Place')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('places.index') }}" class="text-gray-700 hover:text-blue-600">
                        Places
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-gray-500">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Place</h1>

        <form action="{{ route('places.update', $place->id) }}" method="POST" enctype="multipart/form-data" id="edit-place-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                </div>

                <!-- Name -->
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Place Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $place->name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category"
                            name="category"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $place->category) == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="is_active"
                            name="is_active"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="1" {{ old('is_active', $place->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $place->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              required
                              placeholder="Describe this place..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $place->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location Information -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Location Information</h3>
                </div>

                <!-- Address -->
                <div class="lg:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address"
                              name="address"
                              rows="2"
                              required
                              placeholder="Enter the full address..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $place->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coordinates -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                        Latitude
                    </label>
                    <input type="number"
                           id="latitude"
                           name="latitude"
                           step="any"
                           placeholder="e.g., 40.7128"
                           value="{{ old('latitude', $place->latitude) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('latitude') border-red-500 @enderror">
                    @error('latitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                        Longitude
                    </label>
                    <input type="number"
                           id="longitude"
                           name="longitude"
                           step="any"
                           placeholder="e.g., -74.0060"
                           value="{{ old('longitude', $place->longitude) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('longitude') border-red-500 @enderror">
                    @error('longitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Get Location Button -->
                <div class="lg:col-span-2">
                    <button type="button"
                            id="get-location-btn"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium mb-4">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Update Current Location
                    </button>
                </div>

                <!-- Image Upload -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Place Image</h3>
                    @if($place->image ?? false)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $place->image) }}"
                                 alt="{{ $place->name }}"
                                 class="w-32 h-32 object-cover rounded-md">
                            <p class="mt-2 text-sm text-gray-600">Current image</p>
                        </div>
                    @endif

                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $place->image ? 'Upload New Image (Optional)' : 'Upload Image (Optional)' }}
                    </label>
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Supported formats: JPG, PNG, GIF. Max size: 2MB</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                <!-- Delete Button -->
                <button type="button"
                        onclick="deletePlace({{ $place->id }})"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        <path fill-rule="evenodd" d="M10 5a2 2 0 00-2 2v6a2 2 0 004 0V7a2 2 0 00-2-2zm-4 4a4 4 0 118 0v4a4 4 0 01-8 0V9z" clip-rule="evenodd"></path>
                    </svg>
                    Delete Place
                </button>

                <!-- Update and Cancel Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('places.show', $place->id) }}"
                       class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium">
                        Update Place
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Deletion</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $place->name }}"? This action cannot be undone.</p>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button id="confirm-delete-btn"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete Place
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

document.getElementById('get-location-btn').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                showNotification('Location updated successfully!', 'success');
            },
            function(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        showNotification('Location access denied by user.', 'error');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        showNotification('Location information unavailable.', 'error');
                        break;
                    case error.TIMEOUT:
                        showNotification('Location request timed out.', 'error');
                        break;
                    default:
                        showNotification('An unknown error occurred.', 'error');
                        break;
                }
            }
        );
    } else {
        showNotification('Geolocation is not supported by this browser.', 'error');
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Form validation
document.getElementById('edit-place-form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('description').value.trim();
    const address = document.getElementById('address').value.trim();
    const categoryId = document.getElementById('category').value;

    if (!name || !description || !address || !categoryId) {
        e.preventDefault();
        showNotification('Please fill in all required fields.', 'error');
        return false;
    }

    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;

    // Re-enable after a delay
    setTimeout(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
});
</script>
@endpush