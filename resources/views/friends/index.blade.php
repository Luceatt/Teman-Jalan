@extends('layouts.app')

@section('title', 'Friend List')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Friend List') }}</h1>
        
        {{-- Search Section --}}
        <div class="relative w-full md:w-96">
            <form action="{{ route('friends.search') }}" method="GET" class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="query"
                    value="{{ request('query') }}"
                    placeholder="Find friends by name or email..." 
                    class="w-full pl-10 pr-20 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                <button type="submit" class="absolute right-0 top-0 mt-0 mr-0 h-full bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700 transition-colors">
                    Search
                </button>
            </form>
        </div>
    </div>

    {{-- Search Results --}}
    @if(isset($searchResults) && request('query'))
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Search Results') }}</h2>
            @if($searchResults->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($searchResults as $user)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col items-center hover:shadow-md transition-all">
                        <div class="w-20 h-20 bg-blue-100 rounded-full mb-4 flex items-center justify-center text-blue-600 text-2xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="text-center mb-4">
                            <h3 class="font-bold text-lg text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <form action="{{ route('friends.store') }}" method="POST" class="w-full mt-auto">
                            @csrf
                            <input type="hidden" name="friend_id" value="{{ $user->id }}">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Add Friend') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500">{{ __('No users found matching') }} "{{ request('query') }}".</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Pending Requests (Incoming) --}}
    @if($pendingRequests->isNotEmpty())
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $pendingRequests->count() }}</span>
                {{ __('Friend Requests') }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pendingRequests as $request)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 text-lg font-bold">
                                {{ substr($request->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $request->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <form action="{{ route('friends.update', $request->friendship_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                                    {{ __('Accept') }}
                                </button>
                            </form>
                            <form action="{{ route('friends.destroy', $request->friendship_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition-colors">
                                    {{ __('Ignore') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Sent Requests (Outgoing) --}}
    @if($sentRequests->isNotEmpty())
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Sent Requests') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($sentRequests as $request)
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex items-center justify-between opacity-75 hover:opacity-100 transition-opacity">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold">
                                {{ substr($request->friend->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <h3 class="font-medium text-gray-900 truncate">{{ $request->friend->name }}</h3>
                                <p class="text-xs text-gray-500">Pending</p>
                            </div>
                        </div>
                        <form action="{{ route('friends.destroy', $request->friendship_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Cancel Request') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- My Friends List --}}
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('My Friends') }}</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($friends as $friend)
                <div class="group bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 border border-gray-100 p-6 flex flex-col items-center relative">
                    
                    {{-- Remove Friend Button (Top Right) --}}
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                         {{-- We need the friendship ID here. Since we mapped the collection to User models, we might have lost the ID unless we attached it or look it up. 
                            Actually, my controller logic mapped it to the User model effectively stripping the pivot/model data potentially unless I was careful. 
                            Re-checking controller: 
                            `return $friendship->user_id == $userId ? $friendship->friend : $friendship->user;` 
                            Yes, I returned the User model. The User model doesn't have the friendship_id.
                            
                            Critique: I should have kept the Friendship model or attached the ID.
                            Correction: I will fix this in the view by assuming there is a way or I need to fix the controller.
                            
                            Let's fix the controller logic first? Or I can just fetch the friendship ID again? No that's inefficient.
                            I should pass the friendship object OR attach the ID to the user object.
                            
                            Wait, let me pause and check the controller again.
                            
                            In Controller:
                            ->map(function($friendship) use ($userId) {
                                $friend = $friendship->user_id == $userId ? $friendship->friend : $friendship->user;
                                $friend->friendship_id = $friendship->friendship_id; // Attach custom attribute
                                $friend->times_together = $friendship->times_together;
                                return $friend;
                            });
                            
                            I need to update the controller to do this.
                            For now, I'll write the view assuming `friendship_id` exists on the `$friend` object.
                            Then I will immediately update the controller to ensure this data is present.
                         --}}
                        <form action="{{ route('friends.destroy', $friend->friendship_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this friend?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-4 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                        {{ substr($friend->name, 0, 1) }}
                    </div>

                    <div class="text-center mb-4">
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $friend->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                    </div>

                    <div class="w-full pt-4 border-t border-gray-100 flex justify-center">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            {{ __('Together') }} <span class="text-blue-600">{{ $friend->times_together ?? 0 }}</span> {{ __('Times') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-500 rounded-full mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No friends yet') }}</h3>
                    <p class="text-gray-500">{{ __('Search for your friends above to add them to your list within TemanJalan.') }}</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
