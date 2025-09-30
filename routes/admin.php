<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Dash\HomeController;
use App\Http\Controllers\Admin\Dash\UserController;
use App\Http\Controllers\Admin\Dash\OrderController;
use App\Http\Controllers\Admin\Dash\ProductController;
use App\Http\Controllers\Admin\Dash\CategoryController;
use App\Http\Controllers\Admin\Dash\SettingsController;
use App\Http\Controllers\Admin\Dash\ProfileController;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Facades\Route;

// =======================================================================
// ADMIN - Public routes (No login required)
// =======================================================================

Route::middleware('guest.admin')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login-attempt', [LoginController::class, 'loginAttempt'])->name('login.attempt');

    Route::get('/code-verification', [LoginController::class, 'codeVerification'])->name('code.verification');

    // Forget & reset password
    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/check-email', [PasswordController::class, 'showCheckEmail'])->name('check-email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

// =======================================================================
// ADMIN - Private routes (login required)
// =======================================================================

Route::middleware(['auth.admin'])->group(function () {
    // Index
    Route::get('/dashboard', [HomeController::class, 'index'])->name('index');
    // Log out
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/user-photo-upload', [ProfileController::class, 'uploadPhoto'])->name('user.photo.upload');
    });
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/change-password', [SettingsController::class, 'changePassword'])->name('change.password');
        Route::post('/change-password', [SettingsController::class, 'updatePassword'])->name('update.password');
        Route::get('/change-email', [SettingsController::class, 'changeEmail'])->name('change.email');
        Route::post('/change-email', [SettingsController::class, 'updateEmail'])->name('update.email');
        Route::delete('/delete-account', [SettingsController::class, 'deleteAccount'])->name('delete.account');
    });
    // Tables CRUD
    Route::resource('/users', UserController::class);
    Route::resource('/orders', OrderController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/categories', CategoryController::class);
});

//Fallback for admin routes
// Route::fallback(function () {
//     return redirect()->route('404');
// });
