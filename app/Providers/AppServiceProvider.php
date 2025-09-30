<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\GuardAdmin;
use App\Http\Middleware\GuestAdmin;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('auth.admin', GuardAdmin::class);
        Route::aliasMiddleware('guest.admin', GuestAdmin::class);
    }
}
