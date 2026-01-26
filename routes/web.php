<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ActivityController::class, 'dashboard'])->name('home');
    Route::get('activities/export', [ActivityController::class, 'export'])->name('activities.export');
    Route::resource('activities', ActivityController::class)->only(['index', 'create', 'store', 'destroy']);
});
