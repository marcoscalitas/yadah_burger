<?php

namespace App\Providers;

use App\Http\Middleware\GuardAdmin;
use App\Http\Middleware\GuestAdmin;
use App\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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

        // Register event listeners
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class,
        );

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return url(route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });
    }
}
