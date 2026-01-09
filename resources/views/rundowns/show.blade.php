@extends('layouts.app')

@section('title', $rundown->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('rundowns.index') }}"
                   class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $rundown->title }}</h1>
                    <p class="text-gray-600">{{ $rundown->date->format('l, d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($rundown->status === 'published') bg-green-100 text-green-800
                    @elseif($rundown->status === 'completed') bg-blue-100 text-blue-800
                    @elseif($rundown->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $rundown->getStatusLabel() }}
                </span>
                <a href="{{ route('rundowns.expenses.index', $rundown->id) }}"
                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium">
                    {{ __('Expenses') }}
                </a>
                <a href="{{ route('rundowns.edit', $rundown->id) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        @if($rundown->description)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-gray-700">{{ $rundown->description }}</p>
            </div>
        @endif

        <!-- Participants Section -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Participants') }}</h2>
            
            <div class="flex flex-wrap gap-4 mb-4">
                <!-- Creator -->
                <div class="flex items-center space-x-2 bg-blue-50 px-3 py-2 rounded-full border border-blue-100">
                    @if($rundown->creator->profile_photo_url)
                        <img src="{{ $rundown->creator->profile_photo_url }}" alt="{{ $rundown->creator->name }}" class="w-8 h-8 rounded-full">
                    @else
                        <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-xs">
                            {{ substr($rundown->creator->name, 0, 2) }}
                        </div>
                    @endif
                    <div>
                        <span class="text-sm font-medium text-gray-900 block leading-none">{{ $rundown->creator->name }}</span>
                        <span class="text-xs text-blue-600 font-medium">{{ __('Creator') }}</span>
                    </div>
                </div>

                <!-- Other Participants -->
                @foreach($rundown->participants as $participant)
                    <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-full border border-gray-200 group relative">
                        @if($participant->user->profile_photo_url)
                            <img src="{{ $participant->user->profile_photo_url }}" alt="{{ $participant->user->name }}" class="w-8 h-8 rounded-full">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold text-xs">
                                {{ substr($participant->user->name, 0, 2) }}
                            </div>
                        @endif
                        <span class="text-sm font-medium text-gray-900">{{ $participant->user->name }}</span>
                        
                        @if(Auth::id() === $rundown->creator_id)
                            <form action="{{ route('rundowns.participants.destroy', ['rundown' => $rundown->id, 'participant' => $participant->participant_id]) }}" method="POST" class="absolute -top-1 -right-1 hidden group-hover:block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-sm" onclick="return confirm('{{ __('Remove this participant?') }}')">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                <!-- Add Participant Button (Only for Creator) -->
                @if(Auth::id() === $rundown->creator_id)
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full border-2 border-dashed border-gray-300 text-gray-400 hover:border-blue-500 hover:text-blue-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>

                        <!-- Dropdown to add friend -->
                        <div x-show="open" @click.away="open = false" class="absolute top-12 left-0 w-64 bg-white rounded-lg shadow-xl border border-gray-100 z-50 p-2" style="display: none;">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-2">{{ __('Add Friend') }}</h3>
                            
                            @php
                                // Fetch friends logic (simplified for view, ideally passed from controller)
                                $friends = \App\Models\Friendship::where(function($q) {
                                        $q->where('user_id', Auth::id())->orWhere('friend_id', Auth::id());
                                    })
                                    ->where('status', \App\Models\Friendship::STATUS_ACCEPTED)
                                    ->get()
                                    ->map(function($f) {
                                        return $f->user_id == Auth::id() ? $f->friend : $f->user;
                                    })
                                    // Exclude existing participants
                                    ->reject(function($user) use ($rundown) {
                                        return $rundown->participants->contains('user_id', $user->id);
                                    });
                            @endphp

                            @if($friends->isEmpty())
                                <p class="text-sm text-gray-500 px-2 py-1">{{ __('No friends available to add.') }}</p>
                            @else
                                <ul class="max-h-48 overflow-y-auto">
                                    @foreach($friends as $friend)
                                        <li>
                                            <form action="{{ route('rundowns.participants.store', $rundown->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $friend->id }}">
                                                <button type="submit" class="w-full text-left flex items-center px-2 py-2 hover:bg-gray-50 rounded-md">
                                                     @if($friend->profile_photo_url)
                                                        <img src="{{ $friend->profile_photo_url }}" class="w-6 h-6 rounded-full mr-2">
                                                    @else
                                                        <div class="w-6 h-6 rounded-full bg-gray-200 mr-2"></div>
                                                    @endif
                                                    <span class="text-sm text-gray-700">{{ $friend->name }}</span>
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Activities Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Activity Schedule') }}</h2>
                <a href="{{ route('rundowns.activities.create', $rundown->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium">
                    {{ __('Add Activity') }}
                </a>
            </div>

            @if($activities->count() > 0)
                <div class="space-y-4">
                    @foreach($activities as $activity)
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">{{ $loop->iteration }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $activity->name }}</h3>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($activity->isOngoing()) bg-green-100 text-green-800
                                        @elseif($activity->isPast()) bg-gray-100 text-gray-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if($activity->isOngoing()) {{ __('Ongoing') }}
                                        @elseif($activity->isPast()) {{ __('Completed') }}
                                        @else {{ __('Upcoming') }} @endif
                                    </span>
                                </div>
                                @if($activity->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                                @endif
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $activity->start_time->format('H:i') }} - {{ $activity->end_time->format('H:i') }}</span>
                                    <span>{{ $activity->getDurationInMinutes() }} {{ __('minutes') }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">
                                        {{ $activity->place->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex space-x-2">
                                    <a href="{{ route('activities.edit', $activity->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('Edit') }}</a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 text-sm"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this activity?') }}')">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium text-gray-900">{{ __('Total Duration') }}:</span>
                        <span class="text-gray-700">{{ $rundown->getFormattedTotalDuration() }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-2">
                        <span class="font-medium text-gray-900">{{ __('Activities') }}:</span>
                        <span class="text-gray-700">{{ $activities->count() }} {{ __('activities') }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No activities yet') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Add the first activity for this rundown.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('rundowns.activities.create', $rundown->id) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            {{ __('Add Activity') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Map -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Location Map') }}</h2>
                <button onclick="refreshRundownMap({{ $rundown->id }})"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium">
                    {{ __('Refresh Map') }}
                </button>
            </div>

            <div id="map" class="w-full h-96 rounded-lg bg-gray-100"></div>

            @if($places->count() > 0)
                <!-- Places List -->
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">{{ __('Visited Locations') }}:</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($places as $place)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $place->name }}</span>
                                    <p class="text-xs text-gray-600">{{ $place->address }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $place->category->name ?? __('Uncategorized') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">{{ __('Add activities with locations to view map') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
@vite(['resources/js/rundown-map.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initRundownMap({{ $rundown->id }});
    });
</script>
@endpush
@endsection