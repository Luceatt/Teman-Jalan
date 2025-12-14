@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #2d3748; min-height: calc(100vh - 120px);">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            {{-- Welcome Section --}}
            <div class="mb-4">
                <h2 class="text-white mb-1">
                    <i class="fas fa-hand-sparkles me-2"></i>
                    Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p class="text-white-50 mb-0">
                    <i class="far fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                </p>
            </div>

            {{-- Stats Cards Row --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Events</h6>
                                    <h3 class="mb-0">{{ $upcomingEvents->count() }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Places</h6>
                                    <h3 class="mb-0">{{ $totalPlacesVisited }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-map-marked-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Friends</h6>
                                    <h3 class="mb-0">{{ $totalFriends }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-user-friends fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Dashboard Card --}}
            <div class="card shadow-lg border-0" style="background-color: #1a202c; border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Left Side - Upcoming Events (Fixed Height with Scroll) --}}
                        <div class="col-lg-5">
                            <div class="bg-light p-4" style="border-radius: 15px; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); height: 100%;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white rounded-circle p-2 me-2 shadow-sm">
                                        <i class="fas fa-calendar-alt fa-lg" style="color: #667eea;"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0">Upcoming Events</h5>
                                        <small class="text-muted">Event mendatang</small>
                                    </div>
                                </div>
                                
                                @if($upcomingEvents->count() > 0)
                                    {{-- Fixed height container that shows ~2 events, scrollable if more --}}
                                    <div class="event-list-container" style="height: 280px; overflow-y: auto; overflow-x: hidden;">
                                        @foreach($upcomingEvents as $event)
                                            <div class="card border-0 shadow-sm mb-2">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start">
                                                        <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                                            <i class="fas fa-calendar text-primary"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 fw-bold">{{ $event->event_name }}</h6>
                                                            <div class="text-muted small">
                                                                <i class="far fa-clock me-1"></i>
                                                                {{ $event->event_date->format('d M Y') }}
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="badge bg-success small">{{ $event->status }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center" style="height: 280px; display: flex; flex-direction: column; justify-content: center;">
                                        <i class="far fa-calendar-times fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                        <p class="text-muted mb-3">Belum ada event</p>
                                        <button class="btn btn-primary btn-sm shadow-sm">
                                            <i class="fas fa-plus me-1"></i>
                                            Buat Event
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right Side - Tabbed Favorites --}}
                        <div class="col-lg-7">
                            <div class="bg-light p-4 h-100" style="border-radius: 15px;">
                                {{-- Tab Navigation --}}
                                <ul class="nav nav-pills mb-3" id="favoriteTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="places-tab" data-bs-toggle="pill" 
                                                data-bs-target="#places" type="button" role="tab">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            Favorite Places
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="friends-tab" data-bs-toggle="pill" 
                                                data-bs-target="#friends" type="button" role="tab">
                                            <i class="fas fa-user-friends me-2"></i>
                                            Favorite Friends
                                        </button>
                                    </li>
                                </ul>

                                {{-- Tab Content --}}
                                <div class="tab-content" id="favoriteTabsContent">
                                    {{-- Places Tab --}}
                                    <div class="tab-pane fade show active" id="places" role="tabpanel">
                                        @if($favoritePlaces->count() > 0)
                                            <div class="row g-3">
                                                @foreach($favoritePlaces as $place)
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm h-100 hover-lift">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="bg-danger bg-opacity-10 rounded p-3 me-3">
                                                                        <i class="fas fa-map-pin text-danger fa-lg"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-1 fw-bold">{{ $place->place->name }}</h6>
                                                                        <p class="text-muted small mb-2">
                                                                            <i class="fas fa-tag me-1"></i>
                                                                            {{ $place->place->category ?? 'N/A' }}
                                                                        </p>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <span class="badge bg-primary">
                                                                                <i class="fas fa-walking me-1"></i>
                                                                                {{ $place->visit_count }}x kunjungan
                                                                            </span>
                                                                            <small class="text-muted">
                                                                                {{ $place->last_visit_date ? \Carbon\Carbon::parse($place->last_visit_date)->diffForHumans() : 'N/A' }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-map-marked-alt fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                                <p class="text-muted mb-3">Belum ada tempat favorit</p>
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fas fa-plus me-1"></i>
                                                    Tambah Tempat
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Friends Tab --}}
                                    <div class="tab-pane fade" id="friends" role="tabpanel">
                                        @if($favoriteFriends->count() > 0)
                                            <div class="row g-3">
                                                @foreach($favoriteFriends as $friendship)
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm h-100 hover-lift">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                                                        <i class="fas fa-user text-primary fa-lg"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-1 fw-bold">{{ $friendship->friend->name }}</h6>
                                                                        <p class="text-muted small mb-2">
                                                                            {{ $friendship->friend->email }}
                                                                        </p>
                                                                        <span class="badge bg-success">
                                                                            <i class="fas fa-route me-1"></i>
                                                                            {{ $friendship->times_together }}x jalan bareng
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-users fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                                <p class="text-muted mb-3">Belum ada teman favorit</p>
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="fas fa-user-plus me-1"></i>
                                                    Tambah Teman
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    body {
        background-color: #2d3748;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    
    body::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .nav-pills .nav-link {
        color: #6c757d;
        background-color: white;
        border: 1px solid #dee2e6;
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .event-list-container::-webkit-scrollbar {
        width: 4px;
    }
    
    .event-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .event-list-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .event-list-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush
@endsection