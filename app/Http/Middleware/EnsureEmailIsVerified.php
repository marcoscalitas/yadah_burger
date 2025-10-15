<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('admin') ?? auth('admin')->user();

        if (! $user ||
            ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
            ! $user->hasVerifiedEmail()) ||
            in_array($user->user_status, ['p', 'sp', 'd'])) {

            $message = match ($user->user_status ?? null) {
                'p' => 'Sua conta está pendente de verificação.',
                'sp' => 'Sua conta foi suspensa. Entre em contato com o administrador.',
                'd' => 'Sua conta foi desativada. Entre em contato com o administrador.',
                default => 'Seu endereço de email não foi verificado.'
            };

            return $request->expectsJson()
                    ? abort(403, $message)
                    : Redirect::guest(URL::route('admin.verification.notice'));
        }

        return $next($request);
    }
}
