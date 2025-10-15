@extends('layouts.app')

@section('title', 'Edit Rundown')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('rundowns.show', $rundown->id) }}"
                   class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Rundown</h1>
            </div>

            <form action="{{ route('rundowns.update', $rundown->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Rundown *
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $rundown->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Masukkan judul rundown"
                           required>
                    @error('title')
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
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Deskripsikan rundown ini">{{ old('description', $rundown->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div class="mb-6">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal *
                    </label>
                    <input type="date"
                           id="date"
                           name="date"
                           value="{{ old('date', $rundown->date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('date') border-red-500 @enderror"
                           required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status"
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status', $rundown->status) === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>
                        <option value="published" {{ old('status', $rundown->status) === 'published' ? 'selected' : '' }}>
                            Diterbitkan
                        </option>
                        <option value="completed" {{ old('status', $rundown->status) === 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>
                        <option value="cancelled" {{ old('status', $rundown->status) === 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                              placeholder="Catatan tambahan">{{ old('notes', $rundown->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Public -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox"
                               id="is_public"
                               name="is_public"
                               value="1"
                               {{ old('is_public', $rundown->is_public) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">
                            Publik (dapat dilihat oleh orang lain)
                        </label>
                    </div>
                    @error('is_public')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('rundowns.show', $rundown->id) }}"
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
@endsection