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