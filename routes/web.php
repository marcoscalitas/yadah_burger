<?php

use Illuminate\Support\Facades\Route;

// Create admin routes file
Route::prefix('admin')->group(base_path('routes/admin.php'));
