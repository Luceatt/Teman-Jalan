<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\EventHistoryController;
use App\Http\Controllers\PlaceHistoryController;

// Main route history
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

// Route show details
Route::get('/history/events/{eventId}', [EventHistoryController::class, 'show'])->name('history.events.show');
Route::get('/history/places/{placeId}', [PlaceHistoryController::class, 'show'])->name('history.places.show');


use App\Http\Controllers\EventPlanning\ActivityController;
use App\Http\Controllers\EventPlanning\RundownController;
use App\Http\Controllers\Location\PlaceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;


// // Route untuk halaman utama (landing page)
// Route::get('/', function () {
//     return view('welcome');
// });

// Route untuk Authentication (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
});

// Route untuk Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Place management routes
Route::resource('places', PlaceController::class);
Route::get('places-search', [PlaceController::class, 'search'])->name('places.search');
Route::get('places-nearby', [PlaceController::class, 'nearby'])->name('places.nearby');

// Rundown management routes
Route::resource('rundowns', RundownController::class);
Route::post('rundowns/{id}/publish', [RundownController::class, 'publish'])->name('rundowns.publish');
Route::post('rundowns/{id}/complete', [RundownController::class, 'complete'])->name('rundowns.complete');
Route::get('rundowns/{id}/map-data', [RundownController::class, 'getMapData'])->name('rundowns.map-data');
Route::get('rundowns-by-date', [RundownController::class, 'getByDate'])->name('rundowns.by-date');

// Activity management routes
Route::resource('activities', ActivityController::class)->except(['index', 'create', 'store']);
Route::get('rundowns/{rundown}/activities', [ActivityController::class, 'index'])->name('rundowns.activities.index');
Route::get('rundowns/{rundown}/activities/create', [ActivityController::class, 'create'])->name('rundowns.activities.create');
Route::post('rundowns/{rundown}/activities', [ActivityController::class, 'store'])->name('rundowns.activities.store');
Route::post('activities/reorder', [ActivityController::class, 'reorder'])->name('activities.reorder');
Route::get('activities/places/available', [ActivityController::class, 'getAvailablePlaces'])->name('activities.places.available');
Route::get('rundowns/{rundownId}/timeline', [ActivityController::class, 'getTimeline'])->name('activities.timeline');
// Route yang butuh Authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Profile (kalau sudah ada ProfileController)
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});