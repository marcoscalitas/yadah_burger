<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private const ADMIN_AUTH_LOGIN = 'admin.auth.login.';

    public function login()
    {
        return view(self::ADMIN_AUTH_LOGIN.'login');
    }

    private function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Insira um email válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);
    }

    protected function showFormError(Request $request, string $message)
    {
        return back()->withInput()->withErrors([$message]);
    }

    private function checkAccountLocked($user)
    {
        if ($user->account_locked_until && $user->account_locked_until->isFuture()) {
            Carbon::setLocale('pt_BR');
            $remaining = $user->account_locked_until->diffForHumans();

            return "Conta bloqueada temporariamente. Tente novamente $remaining.";
        }

        return null;
    }

    private function incrementFailedAttempts($user)
    {
        $user->increment('failed_login_attempts');

        if ($user->failed_login_attempts >= 3) {
            $user->update([
                'account_locked_until' => now()->addMinutes(5),
                'failed_login_attempts' => 0,
            ]);

            return 'Conta bloqueada por tentativas incorretas. Tente novamente em 5 minutos.';
        }

        return 'Email ou senha incorretos. Tente novamente.';
    }

    private function resetLoginAttempts($user)
    {
        $user->update([
            'failed_login_attempts' => 0,
            'account_locked_until' => null,
            'is_online' => true,
            'last_login' => now(),
        ]);
    }

    public function loginAttempt(Request $request)
    {
        $auth = auth('admin');
        $this->validateLogin($request);

        $user = User::where('email', $request->email)->first();
        $remember = $request->has('remember');

        if ($user) {
            if ($msg = $this->checkAccountLocked($user)) {
                return $this->showFormError($request, $msg);
            }
        }

        $credentials = $request->only('email', 'password');

        if (! $auth->attempt($credentials, $remember)) {
            return $user
                ? $this->showFormError($request, $this->incrementFailedAttempts($user))
                : $this->showFormError($request, 'Email ou senha incorretos. Tente novamente.');
        }

        $user = $auth->user();

        if (! in_array($user->role, ['admin', 'staff'])) {
            $auth->logout();

            return $this->showFormError($request, 'Acesso restrito. Você não tem permissão para acessar esta área.');
        }

        $this->resetLoginAttempts($user);
        $request->session()->regenerate();

        return redirect()->route('admin.index');
    }

    public function logout(Request $request)
    {
        $auth = auth('admin');
        $user = $auth->user();

        if ($user) {
            $user->forceFill([
                'is_online' => 0,
                'remember_token' => null,
            ])->save();
        }

        $auth->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function codeVerification()
    {
        return view(self::ADMIN_AUTH_LOGIN.'code-verification');
    }
}
