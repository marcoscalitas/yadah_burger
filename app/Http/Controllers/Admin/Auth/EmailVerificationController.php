<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user('admin') ?? auth('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Verificar se o usuário pode acessar o dashboard (email verificado E status ativo)
        $canAccessDashboard = $user->hasVerifiedEmail() && $user->user_status === 'a';

        return $canAccessDashboard
            ? redirect()->intended(route('admin.index', absolute: false))
            : view('admin.auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request): RedirectResponse
    {
        // Get user from the signed URL parameters
        $userId = $request->route('id');
        $user = \App\Models\User::find($userId);

        // Check if user exists
        if (!$user) {
            abort(404, 'Usuário não encontrado.');
        }

        // Verify the hash matches
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Link de verificação inválido.');
        }

        // SECURITY FIX: Verificar se o email não foi alterado após o link ser gerado
        // Se o usuário já tem email verificado MAS o status está pendente,
        // significa que o email foi alterado e este é um link antigo
        if ($user->hasVerifiedEmail() && $user->user_status === 'a') {
            // Email já verificado e conta já ativa - link desnecessário mas válido
            auth('admin')->login($user);
            return redirect()->intended(route('admin.index', absolute: false).'?verified=1')
                ->with('info', 'Email já estava verificado! Bem-vindo de volta.');
        }

        // Se chegou aqui, é um link válido para verificação
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Ativar automaticamente usuários que verificaram o email
        if ($user->user_status === 'p') {
            $user->update(['user_status' => 'a']);
        }

        // Log the user in after verification
        auth('admin')->login($user);

        // Agora que o usuário está ativo, redirecionar para o dashboard
        return redirect()->intended(route('admin.index', absolute: false).'?verified=1')
            ->with('success', 'Email verificado e conta ativada com sucesso!');
    }

    /**
     * Send a new email verification notification.
     */
    public function send(Request $request): RedirectResponse
    {
        $user = $request->user('admin') ?? auth('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if ($user->hasVerifiedEmail() && $user->user_status === 'a') {
            return redirect()->intended(route('admin.index', absolute: false));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
