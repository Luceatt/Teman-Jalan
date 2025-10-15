<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;

Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
