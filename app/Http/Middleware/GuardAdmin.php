<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuardAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Checa se o usuário está logado usando o guard 'admin'
        if (!Auth::guard('admin')->check()) {
            // Redireciona para a rota de login do admin
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
