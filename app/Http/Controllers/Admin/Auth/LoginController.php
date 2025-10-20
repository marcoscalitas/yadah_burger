<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    private const ADMIN_AUTH_LOGIN = 'admin.auth.';

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
        // Guarda de autenticação
        $auth = auth('admin');
        $this->validateLogin($request);

        $remember = $request->boolean('remember', false);

        // === 1. Buscar usuário de forma segura ===
        $user = User::where('email', $request->email)->first();

        // Hash dummy (para timing seguro mesmo se o usuário não existir)
        $dummyHash = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $passwordHash = $user ? $user->password : $dummyHash;

        // Verificação de senha — timing-safe
        $passwordValid = Hash::check($request->password, $passwordHash);

        // === 2. Falha imediata se usuário não existe (tempo igual) ===
        if (! $user) {
            // Mesmo retorno para não revelar existência de conta
            return $this->showFormError($request, 'Email ou senha incorretos. Tente novamente.');
        }

        // === 3. Verificar se a conta está bloqueada ou suspensa ===
        if ($msg = $this->checkAccountLocked($user)) {
            return $this->showFormError($request, $msg);
        }

        // === 4. Checar role ===
        if (! in_array($user->role, ['admin', 'staff'])) {
            return $this->showFormError($request, 'Acesso restrito. Você não tem permissão para acessar esta área.');
        }

        // === 5. Validar senha ===
        if (! $passwordValid) {
            return $this->showFormError($request, $this->incrementFailedAttempts($user));
        }

        // === 6. Verificar status da conta antes do login ===
        $isEmailVerifiable = $user instanceof MustVerifyEmail;
        $isPendingVerification = $user->user_status === 'p' || ($isEmailVerifiable && ! $user->hasVerifiedEmail());
        $restrictedStatus = in_array($user->user_status, ['sp', 'd']);

        if ($isPendingVerification || $restrictedStatus) {
            // Reseta tentativas mesmo se login não permitido
            $this->resetLoginAttempts($user);

            $message = match (true) {
                $isPendingVerification => 'Sua conta está pendente de verificação. Contate o administrador.',
                $user->user_status === 'sp' => 'Sua conta foi suspensa. Contate o administrador.',
                $user->user_status === 'd' => 'Sua conta foi desativada. Contate o administrador.',
                default => 'Acesso temporariamente bloqueado. Tente novamente mais tarde.',
            };

            return $this->showFormError($request, $message);
        }

        // === 7. Autenticar o usuário somente agora ===
        $auth->login($user, $remember);

        // === 8. Segurança pós-login ===
        $this->resetLoginAttempts($user);
        $request->session()->regenerate();

        Log::info('Login bem-sucedido', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

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
}
