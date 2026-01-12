<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ActivityController::class, 'dashboard'])->name('home');

Route::get('activities/export', [ActivityController::class, 'export'])->name('activities.export');
Route::resource('activities', ActivityController::class)->only(['index', 'create', 'store', 'destroy']);
