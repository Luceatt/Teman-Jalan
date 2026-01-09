<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\EventHistoryController;
use App\Http\Controllers\PlaceHistoryController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Language Switcher
Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Guest Routes (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Friends
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::patch('/friends/{friendship}', [FriendController::class, 'update'])->name('friends.update');
    Route::delete('/friends/{friendship}', [FriendController::class, 'destroy'])->name('friends.destroy');

    // History (completed events)
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/events/{eventId}', [EventHistoryController::class, 'show'])->name('events.show');
        Route::get('/places/{placeId}', [PlaceHistoryController::class, 'show'])->name('places.show');
    });

    // Places (Tempat) Management
    Route::get('places/search', [PlaceController::class, 'search'])->name('places.search');
    Route::resource('places', PlaceController::class);

    // Rundown Management (trip planning)
    Route::post('rundowns/{id}/publish', [RundownController::class, 'publish'])->name('rundowns.publish');
    Route::post('rundowns/{id}/complete', [RundownController::class, 'complete'])->name('rundowns.complete');
    Route::get('rundowns/{id}/map-data', [RundownController::class, 'getMapData'])->name('rundowns.map-data');
    Route::get('rundowns-by-date', [RundownController::class, 'getByDate'])->name('rundowns.by-date');
    Route::resource('rundowns', RundownController::class);

    // Activity Management (within rundowns)
    Route::prefix('rundowns/{rundown}')->name('rundowns.')->group(function () {
        Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('activities', [ActivityController::class, 'store'])->name('activities.store');
        
        // Expense Routes
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });
    
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::post('reorder', [ActivityController::class, 'reorder'])->name('reorder');
        Route::get('places/available', [ActivityController::class, 'getAvailablePlaces'])->name('places.available');
    });
    
    Route::get('rundowns/{rundownId}/timeline', [ActivityController::class, 'getTimeline'])->name('activities.timeline');
    Route::resource('activities', ActivityController::class)->except(['index', 'create', 'store']);
});