@extends('layouts.app')

@section('title', 'History')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-900">History</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        {{-- Toggle Tabs --}}
        <div class="flex border-b border-gray-200">
            <button id="btnEvents" class="w-1/2 py-4 text-center font-bold text-lg cursor-pointer transition-colors duration-200 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                Events
            </button>
            <button id="btnPlaces" class="w-1/2 py-4 text-center font-bold text-lg cursor-pointer transition-colors duration-200 bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none">
                Places
            </button>
        </div>

        {{-- Content Area --}}
        <div class="p-6 text-center min-h-[300px]">
            <div id="eventsContent">
                @include('history.partials.events-content')
            </div>

            <div id="placesContent" class="hidden">
                @include('history.partials.places-content')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEvents = document.getElementById('btnEvents');
        const btnPlaces = document.getElementById('btnPlaces');
        const eventsContent = document.getElementById('eventsContent');
        const placesContent = document.getElementById('placesContent');

        function setActive(activeBtn, inactiveBtn, activeContent, inactiveContent) {
            // Update buttons
            activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            
            inactiveBtn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            inactiveBtn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');

            // Update content with transition logic if desired, for now simple display toggle
            activeContent.classList.remove('hidden');
            inactiveContent.classList.add('hidden');
        }

        btnEvents.addEventListener('click', () => {
            setActive(btnEvents, btnPlaces, eventsContent, placesContent);
        });

        btnPlaces.addEventListener('click', () => {
            setActive(btnPlaces, btnEvents, placesContent, eventsContent);
        });
    });
</script>
@endpush
@endsection
