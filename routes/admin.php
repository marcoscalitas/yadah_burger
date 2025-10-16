<?php

use App\Http\Controllers\Admin\Auth\EmailVerificationController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Dash\CategoryController;
use App\Http\Controllers\Admin\Dash\HomeController;
use App\Http\Controllers\Admin\Dash\OrderController;
use App\Http\Controllers\Admin\Dash\ProductController;
use App\Http\Controllers\Admin\Dash\ProfileController;
use App\Http\Controllers\Admin\Dash\SettingsController;
use App\Http\Controllers\Admin\Dash\UserController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

// =======================================================================
// ADMIN - Public routes (No login required)
// =======================================================================

Route::middleware('guest.admin')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login-attempt', [LoginController::class, 'loginAttempt'])
        ->middleware('throttle:5,1') // Máximo 5 tentativas por minuto por IP
        ->name('login.attempt');

    // Forget & reset password
    Route::controller(PasswordController::class)->group(function () {
        Route::get('/forgot-password', 'showForgotForm')->name('password.request');
        Route::post('/forgot-password', 'sendResetLink')->name('password.email');
        Route::get('/check-email', 'showCheckEmail')->name('check.email');
        Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
        Route::post('/reset-password', 'reset')->name('password.update');
    });
});

// =======================================================================
// ADMIN - Email Verification routes (public access needed for verification link)
// =======================================================================

// Email verification link (must be accessible without auth for email links)
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Email verification notice and resend (requires auth)
Route::middleware('auth.admin')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Log out (disponível para todos os usuários logados, mesmo não verificados)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// =======================================================================
// ADMIN - Private routes (login required + verified)
// =======================================================================

Route::middleware(['auth.admin', 'verified'])->group(function () {
    // Index
    Route::get('/dashboard', [HomeController::class, 'index'])->name('index');
    // Profile
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::post('/user-photo-upload', 'uploadPhoto')->name('user.photo.upload');
    });
    // Settings
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/change-password', 'changePassword')->name('change.password');
        Route::post('/change-password', 'updatePassword')->name('update.password');
        Route::get('/change-email', 'changeEmail')->name('change.email');
        Route::post('/change-email', 'updateEmail')->name('update.email');
        Route::delete('/delete-account', 'deleteAccount')->name('delete.account');
    });
    // Tables CRUD
    Route::resource('/users', UserController::class)->except(['show']);
    Route::resource('/orders', OrderController::class);
    Route::resource('/products', ProductController::class)->except(['show']);
    Route::resource('/categories', CategoryController::class)->except(['show']);

    // Additional order routes
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');

    // Utils
    Route::post('/users/{id}/update-photo', [UserController::class, 'uploadPhoto'])->name('users.update.photo');
});
