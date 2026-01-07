@extends('layouts.app')

@section('title', 'Friend List')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-900">Friend List</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($friends as $f)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-200">
                <div class="w-20 h-20 bg-gray-800 rounded-full mb-4 flex items-center justify-center text-white text-2xl font-bold">
                    {{-- Avatar placeholder or initial --}}
                    {{ substr($f->friend->name ?? '?', 0, 1) }}
                </div>

                <div class="font-bold text-lg text-gray-900 mb-2">
                    {{ $f->friend->name }}
                </div>

                <div class="text-sm text-gray-500 mb-4">
                    Went out together <span class="font-semibold text-gray-700">{{ $f->times_together ?? 0 }}</span> times
                </div>

                <div class="flex space-x-2 w-full mt-auto">
                    <a href="#" class="flex-1 bg-green-100 text-green-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-green-200 transition-colors">
                        History
                    </a>
                    <a href="#" class="flex-1 bg-green-100 text-green-700 px-3 py-2 rounded-md text-sm font-medium hover:bg-green-200 transition-colors">
                        Invites
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="inline-flex items-center justify-center p-4 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-user-friends text-gray-400 text-3xl"></i>
                </div>
                <p class="text-gray-500 text-lg">No friends found yet.</p>
                <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Find Friends
                </button>
            </div>
        @endforelse
    </div>
</div>
@endsection
