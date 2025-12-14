<?php

use Illuminate\Support\Facades\Route;
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

// Route yang butuh Authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Profile (kalau sudah ada ProfileController)
    // Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});