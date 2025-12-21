@extends('layouts.app')

@section('title', 'Edit Aktivitas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('rundowns.show', $activity->rundown_id) }}"
                   class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Aktivitas</h1>
                    <p class="text-gray-600">Untuk rundown: {{ $activity->rundown->title }}</p>
                </div>
            </div>

            <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Aktivitas *
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $activity->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Masukkan nama aktivitas"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Deskripsikan aktivitas ini">{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Place -->
                <div class="mb-6">
                    <label for="place_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi *
                    </label>
                    <select id="place_id"
                            name="place_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('place_id') border-red-500 @enderror"
                            required>
                        <option value="{{ $activity->place_id }}" selected>{{ $activity->place->name }}</option>
                    </select>
                    @error('place_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start and End Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Mulai *
                        </label>
                        <input type="datetime-local"
                               id="start_time"
                               name="start_time"
                               value="{{ old('start_time', $activity->start_time->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror"
                               required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Selesai *
                        </label>
                        <input type="datetime-local"
                               id="end_time"
                               name="end_time"
                               value="{{ old('end_time', $activity->end_time->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror"
                               required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea id="notes"
                              name="notes"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Catatan tambahan untuk aktivitas ini">{{ old('notes', $activity->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('rundowns.show', $activity->rundown_id) }}"
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const placeSelect = document.getElementById('place_id');

    // Initialize TomSelect for place selection
    const tomSelect = new TomSelect(placeSelect, {
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        create: false,
        load: function(query, callback) {
            const url = `{{ route('activities.places.available') }}?search=${encodeURIComponent(query)}`;
            fetch(url)
                .then(response => response.json())
                .then(json => {
                    callback(json);
                }).catch(() => {
                    callback();
                });
        },
        render: {
            option: function(data, escape) {
                return `<div class="flex items-center">
                            <div>
                                <div class="font-semibold">${escape(data.name)}</div>
                                <div class="text-sm text-gray-500">${escape(data.address)}</div>
                            </div>
                        </div>`;
            },
            item: function(data, escape) {
                return `<div>${escape(data.name)}</div>`;
            }
        }
    });
});
</script>
@endpush