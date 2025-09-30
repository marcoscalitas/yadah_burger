<?php

use Illuminate\Support\Facades\Route;

// Create admin routes file
Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

// Fallback for admin routes
// Route::fallback(function () {
//     return dd("404 ERROR");
// });
