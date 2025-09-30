<?php

use Illuminate\Support\Facades\Route;

// Create admin routes file
Route::prefix('admin')->name('admin.')->group(function () {
    require base_path('routes/admin.php');
});

Route::fallback(function () {
    return response()->view('shared.errors.error-404', [], 404);
});
