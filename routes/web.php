<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class , 'authenticate'])->name('authenticate');
});

Route::post('/logout', [AuthController::class , 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ActivityController::class , 'dashboard'])->name('home');
    Route::get('activities/export', [ActivityController::class , 'export'])->name('activities.export');
    Route::resource('activities', ActivityController::class)->only(['index', 'create', 'store', 'destroy']);

    // Update status (user & admin, with controller-level permission check)
    Route::patch('activities/{activity}/status', [ActivityController::class , 'updateStatus'])->name('activities.updateStatus');

// Admin-only: destroy (double protection)
// Route::delete('activities/{activity}', [ActivityController::class, 'destroy'])
//     ->name('activities.destroy')
//     ->middleware(AdminMiddleware::class);
});

// Storage fallback route
Route::get('/storage/activities/{filename}', function ($filename) {
    $path = public_path('activities/' . $filename);

    if (!\Illuminate\Support\Facades\File::exists($path)) {
        $path = storage_path('app/public/activities/' . $filename);
    }

    if (!\Illuminate\Support\Facades\File::exists($path)) {
        abort(404);
    }

    $file = \Illuminate\Support\Facades\File::get($path);
    $type = \Illuminate\Support\Facades\File::mimeType($path);

    return response($file, 200)->header('Content-Type', $type);
});