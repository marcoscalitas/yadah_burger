<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    private const ADMIN_AUTH_PASSWORD = 'admin.auth.';

    public function showForgotForm()
    {
        return view(self::ADMIN_AUTH_PASSWORD.'forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        // Log da tentativa para auditoria
        Log::info('Tentativa de reset de senha', [
            'email' => $request->get('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $status,
            'timestamp' => now(),
        ]);

        // ABORDAGEM EQUILIBRADA: Melhor UX mantendo segurança razoável
        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('admin.check.email')->with(['email' => $request->get('email')]);
        }

        // Para emails inexistentes, dar feedback útil mas genérico
        return back()->withErrors([
            'email' => 'Não encontrar nenhum utilizador com este endereço de e-mail.',
        ])->withInput();
    }

    public function showCheckEmail(Request $request)
    {
        $email = session('email');

        return view(self::ADMIN_AUTH_PASSWORD.'check-email', compact('email'));
    }

    public function showResetForm(Request $request, $token)
    {
        return view(self::ADMIN_AUTH_PASSWORD.'reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // SECURITY FIX: Verificar se o usuário existe e tem status válido para reset
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        if ($user->user_status === 'd') {
            return back()->withErrors(['email' => 'Conta desativada. Contacte o administrador.']);
        }

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'user_status' => 'a', // Ativar usuário após reset bem-sucedido
                ])->save();

                // Log da ação de reset
                Log::info('Password reset realizado com sucesso', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('success', 'Senha redefinida com sucesso! Faça login com a nova senha.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
