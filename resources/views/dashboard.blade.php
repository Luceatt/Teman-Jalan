@extends('layouts.app')

@section('title', 'Dashboard - Teman Jalan')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Welcome Section --}}
        <div class="mb-8 bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-8 shadow-lg text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-blue-500 opacity-10 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2 flex items-center">
                    <i class="fas fa-hand-sparkles mr-3 text-yellow-400"></i>
                    {{ __('Welcome') }}, {{ Auth::user()->name }}!
                </h2>
                <p class="text-gray-300 text-lg flex items-center">
                    <i class="far fa-calendar mr-2"></i>
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                </p>
            </div>
        </div>

        {{-- Stats Cards Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Events Card --}}
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center text-white">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">{{ __('Total Events') }}</p>
                            <h3 class="text-3xl font-bold">{{ $upcomingEvents->count() }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Places Card --}}
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center text-white">
                        <div>
                            <p class="text-purple-100 text-sm font-medium uppercase tracking-wider mb-1">{{ __('Total Places') }}</p>
                            <h3 class="text-3xl font-bold">{{ $totalPlacesVisited }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-map-marked-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Friends Card --}}
            <div class="bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center text-white">
                        <div>
                            <p class="text-cyan-100 text-sm font-medium uppercase tracking-wider mb-1">{{ __('Total Friends') }}</p>
                            <h3 class="text-3xl font-bold">{{ $totalFriends }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <i class="fas fa-user-friends text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Side - Upcoming Events --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center">
                            <div class="bg-blue-100 text-blue-600 rounded-lg p-2 mr-3">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <h5 class="text-lg font-bold text-gray-800">{{ __('Upcoming Events') }}</h5>
                                <p class="text-sm text-gray-500">{{ __('Your upcoming events') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 overflow-y-auto flex-1 custom-scrollbar" style="max-height: 400px;">
                        @if($upcomingEvents->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingEvents as $event)
                                    <div class="group bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 bg-blue-50 text-blue-600 rounded-lg p-3 mr-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                                <div class="text-center w-8">
                                                    <span class="block text-xs font-bold uppercase">{{ $event->event_date->format('M') }}</span>
                                                    <span class="block text-xl font-bold">{{ $event->event_date->format('d') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow">
                                                <h6 class="text-base font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">{{ $event->event_name }}</h6>
                                                <div class="flex items-center text-sm text-gray-500 mb-2">
                                                    <i class="far fa-clock mr-2"></i>
                                                    {{ $event->event_date->format('l, H:i') }}
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $event->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-64 flex flex-col justify-center items-center text-center p-6">
                                <div class="bg-gray-50 rounded-full p-4 mb-4">
                                    <i class="far fa-calendar-times text-gray-300 text-4xl"></i>
                                </div>
                                <h6 class="text-gray-900 font-medium mb-1">{{ __('No events yet') }}</h6>
                                <p class="text-gray-500 text-sm mb-4">{{ __('Start planning your trip now!') }}</p>
                                <a href="{{ route('rundowns.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ __('Create Event') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Side - Tabbed Favorites --}}
            <div class="lg:col-span-7">
                <div x-data="{ activeTab: 'places' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="p-2 border-b border-gray-100">
                        <div class="flex space-x-2 bg-gray-50 p-1 rounded-xl">
                            <button @click="activeTab = 'places'" 
                                    :class="{ 'bg-white text-blue-600 shadow-sm': activeTab === 'places', 'text-gray-500 hover:text-gray-700': activeTab !== 'places' }"
                                    class="flex-1 flex items-center justify-center py-2.5 px-4 rounded-lg text-sm font-medium transition-all duration-200">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ __('Favorite Places') }}
                            </button>
                            <button @click="activeTab = 'friends'" 
                                    :class="{ 'bg-white text-blue-600 shadow-sm': activeTab === 'friends', 'text-gray-500 hover:text-gray-700': activeTab !== 'friends' }"
                                    class="flex-1 flex items-center justify-center py-2.5 px-4 rounded-lg text-sm font-medium transition-all duration-200">
                                <i class="fas fa-user-friends mr-2"></i>
                                {{ __('Favorite Friends') }}
                            </button>
                        </div>
                    </div>

                    <div class="p-6 flex-1 bg-gray-50 bg-opacity-50">
                        {{-- Places Tab --}}
                        <div x-show="activeTab === 'places'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            @if($favoritePlaces->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($favoritePlaces as $place)
                                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                                            <div class="flex items-start space-x-4">
                                                <div class="bg-red-50 text-red-500 rounded-lg p-3 flex-shrink-0">
                                                    <i class="fas fa-map-pin text-xl"></i>
                                                </div>
                                                <div class="flex-grow min-w-0">
                                                    <h6 class="text-sm font-bold text-gray-900 truncate">{{ $place->place->name }}</h6>
                                                    <p class="text-xs text-gray-500 mb-2 truncate">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $place->place->category ?? 'General' }}
                                                    </p>
                                                    <div class="flex justify-between items-center">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                            <i class="fas fa-walking mr-1"></i>
                                                            {{ $place->visit_count }}x
                                                        </span>
                                                        <span class="text-xs text-gray-400">
                                                            {{ $place->last_visit_date ? \Carbon\Carbon::parse($place->last_visit_date)->diffForHumans() : 'Never' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-white rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <i class="fas fa-map-marked-alt text-gray-300 text-3xl"></i>
                                    </div>
                                    <h3 class="text-gray-900 font-medium mb-1">{{ __('No favorite places yet') }}</h3>
                                    <p class="text-gray-500 text-sm mb-6">{{ __('Explore new places and add them to favorites!') }}</p>
                                    <a href="{{ route('places.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                                        <i class="fas fa-plus mr-2"></i>
                                        {{ __('Add Place') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Friends Tab --}}
                        <div x-show="activeTab === 'friends'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;">
                            @if($favoriteFriends->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($favoriteFriends as $friendship)
                                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                                            <div class="flex items-center space-x-4">
                                                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-full h-12 w-12 flex items-center justify-center text-white shadow-sm flex-shrink-0">
                                                    <span class="font-bold text-lg">{{ substr($friendship->friend->name, 0, 1) }}</span>
                                                </div>
                                                <div class="flex-grow min-w-0">
                                                    <h6 class="text-sm font-bold text-gray-900 truncate">{{ $friendship->friend->name }}</h6>
                                                    <p class="text-xs text-gray-500 mb-2 truncate">
                                                        {{ $friendship->friend->email }}
                                                    </p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                                                        <i class="fas fa-route mr-1"></i>
                                                        {{ $friendship->times_together }}x {{ __('times together') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-white rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <i class="fas fa-users text-gray-300 text-3xl"></i>
                                    </div>
                                    <h3 class="text-gray-900 font-medium mb-1">{{ __('No favorite friends yet') }}</h3>
                                    <p class="text-gray-500 text-sm mb-6">{{ __('Invite your friends to join and go out together!') }}</p>
                                    <a href="{{ url('/friends') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        {{ __('Add Friend') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection