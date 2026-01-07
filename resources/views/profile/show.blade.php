@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 sm:p-8">
            {{-- Header --}}
            <div class="flex items-center mb-8 border-b border-gray-100 pb-8">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-4xl uppercase">
                         {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-gray-900 font-serif">{{ $user->name }}</h1>
                    <p class="text-lg text-gray-500 font-serif">User</p>
                </div>
            </div>

            {{-- Form Fields (Read Only for now based on original view) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                     <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-full bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ $user->name }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-full bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ $user->email }}" readonly>
                    </div>

                     <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Favorite Place</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-full bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="-" readonly>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        {{-- Hiding actual hash in UI generally better, but keeping original logic --}}
                         <input type="password" class="w-full px-4 py-2 border border-gray-300 rounded-full bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="********" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Favorite Friend</label>
                         <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-full bg-gray-50 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="-" readonly>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="button" class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors shadow-sm">
                    Edit Profile
                </button>
            </div>
        </div>
    </div>
</div>
@endsection