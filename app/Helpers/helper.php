<?php

use App\Services\UploadService;
use Illuminate\Http\Request;

if (! function_exists('getBreadcrumb')) {
    /**
     * Retorna o breadcrumb configurado com as rotas resolvidas.
     *
     * @param  string  $key  Nome da trail (ex: 'admin.users.show')
     * @param  array  $extraItems  Itens extras (ex: [['label' => 'João Silva']])
     * @param  string|null  $customTitle  Sobrescreve o título padrão (opcional)
     */
    function getBreadcrumb(string $key, array $extraItems = [], ?string $customTitle = null): array
    {
        $breadcrumbs = config('breadcrumbs');
        $config = $breadcrumbs[$key] ?? null;

        if (! $config) {
            return [
                'title' => $customTitle ?? 'Página',
                'items' => [['label' => 'Home', 'url' => route('admin.index')]],
            ];
        }

        // Resolve rotas para URLs
        $items = array_map(function ($item) {
            if (isset($item['route'])) {
                $params = $item['route_params'] ?? [];
                $item['url'] = route($item['route'], $params);
            }

            return $item;
        }, $config['items']);

        // Junta com itens extras (ex: nome do utilizador)
        $items = array_merge($items, $extraItems);

        return [
            'title' => $customTitle ?? ($config['title'] ?? 'Página'),
            'items' => $items,
        ];
    }
}

// handlePhotoUpload
if (! function_exists('handlePhotoUpload')) {
    function handlePhotoUpload(Request $request, string $folder, string $field): string
    {
        try {
            $fileData = (new UploadService)->configure([
                'mimes' => ['jpg', 'jpeg', 'png'],
                'maxSizeMB' => 3,
                'folder' => $folder,
                'disk' => 'public',
            ])->upload($request->file($field));

            return $fileData['path'];
        } catch (\Throwable $e) {
            Log::error('Erro no upload da foto: '.$e->getMessage());
            throw new \Exception('Erro ao enviar foto: '.$e->getMessage());
        }
    }
}

// getStatusBadge
if (! function_exists('getStatusBadge')) {
    function getStatusBadge($status): string
    {
        $badge = fn ($class, $label) => "<span class=\"badge text-{$class}-500 bg-{$class}-500/15\">$label</span>";

        $badges = [
            'i' => $badge('warning', 'Inativo'),
            'p' => $badge('primary', 'Pendente'),
            'a' => $badge('success', 'Ativado'),
            'sp' => $badge('warning', 'Suspenso'),
            'd' => $badge('danger', 'Apagado'),
        ];

        return $badges[$status] ?? $badge('dark', '-');
    }
}

// Get current User
if (! function_exists('getCurrentUser')) {
    function getCurrentUser($role = '')
    {
        $user = auth($role)->user() ?? auth()->user();

        return $user;
    }
}

// isAdmin
if (! function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        $user = getCurrentUser('admin');

        return $user && $user->role === 'admin';
    }
}

// isRouteActive - Verifica se uma rota ou grupo de rotas está ativo
if (! function_exists('isRouteActive')) {
    /**
     * Verifica se a rota atual corresponde a um ou mais padrões fornecidos.
     * Aceita wildcards usando o caractere '*'.
     */
    function isRouteActive(array|string $routePatterns): bool
    {
        $currentRoute = \Route::currentRouteName();

        if (empty($currentRoute)) {
            return false;
        }

        // Garante que estamos lidando com um array
        $patterns = is_array($routePatterns) ? $routePatterns : [$routePatterns];

        foreach ($patterns as $pattern) {
            // Se contém asterisco, trata como wildcard
            if (str_contains($pattern, '*')) {
                // Escapa os caracteres especiais e substitui * por .*
                $escaped = preg_quote($pattern, '/');
                $regex = '/^'.str_replace('\*', '.*', $escaped).'$/';

                if (preg_match($regex, $currentRoute)) {
                    return true;
                }
            }
            // Verificação exata
            elseif ($currentRoute === $pattern) {
                return true;
            }
        }

        return false;
    }
}

// getActiveClass - Retorna a classe CSS se a rota estiver ativa
if (! function_exists('getActiveClass')) {
    /**
     * Retorna uma classe CSS com base no estado ativo da rota.
     */
    function getActiveClass(array|string $routePatterns, string $activeClass = 'active', string $inactiveClass = ''): string
    {
        return isRouteActive($routePatterns) ? $activeClass : $inactiveClass;
    }
}

// get Formatted Date
if (! function_exists('getFormattedDate')) {
    function getFormattedDate($value, string $format = 'd-m-Y'): ?string
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof \Carbon\Carbon) {
            $date = $value;
        } else {
            try {
                $date = \Carbon\Carbon::parse($value);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $date->format($format);
    }
}

// Check if is Admin
if (! function_exists('checkIfIsAdmin')) {
    function checkIfIsAdmin(string $crudAction, string $entity)
    {
        if (! isAdmin()) {
            return abort(403, "Acesso negado. Apenas administradores podem {$crudAction} {$entity}.");
        }
    }
}

// Handle User Email Update - Processa atualização de email com verificação automática
if (! function_exists('handleUserEmailUpdate')) {
    /**
     * Processa a atualização de dados do usuário, verificando mudança de email
     * e aplicando reset de status e envio de verificação automaticamente.
     *
     * @return string Mensagem de sucesso
     */
    function handleUserEmailUpdate(\App\Models\User $user, array $data, bool $isSelfUpdate = false, ?int $updatedBy = null): string
    {
        $originalEmail = $user->email;
        $emailChanged = $originalEmail !== ($data['email'] ?? $originalEmail);

        // Reset status se email mudou
        if ($emailChanged) {
            $data['user_status'] = 'p';
            $data['email_verified_at'] = null;

            // SECURITY FIX: Invalidar todos os tokens antigos
            invalidateOldVerificationTokens($user->id);
            invalidatePasswordResetTokens($originalEmail);
        } // Atualizar usuário

        $user->update($data);

        // Se email mudou, enviar verificação e log
        if ($emailChanged) {
            event(new \Illuminate\Auth\Events\Registered($user));

            $logData = [
                'user_id' => $user->id,
                'old_email' => $originalEmail,
                'new_email' => $data['email'],
            ];

            if ($isSelfUpdate) {
                $logData['self_update'] = true;
                $logMessage = 'Email do próprio utilizador alterado - Status resetado e email de verificação enviado';
            } else {
                $logData['updated_by'] = $updatedBy;
                $logMessage = 'Email do utilizador alterado - Status resetado e email de verificação enviado';
            }

            \Illuminate\Support\Facades\Log::info($logMessage, $logData);
        }

        // Retornar mensagem apropriada
        $baseMessage = $isSelfUpdate ? 'Perfil atualizado com sucesso' : 'Utilizador atualizado com sucesso';

        return $emailChanged ? $baseMessage.'! Email de verificação enviado para o novo endereço.' : $baseMessage.'!';
    }
}

// Invalidate Old Verification Tokens - Invalida tokens de verificação antigos
if (! function_exists('invalidateOldVerificationTokens')) {
    /**
     * Invalida todos os tokens de verificação de email antigos para um usuário.
     */
    function invalidateOldVerificationTokens(int $userId): void
    {
        try {
            \Illuminate\Support\Facades\DB::table('email_verification_tokens')
                ->where('user_id', $userId)
                ->where('used', false)
                ->update(['used' => true, 'updated_at' => now()]);

            \Illuminate\Support\Facades\Log::info('Tokens de verificação antigos invalidados', [
                'user_id' => $userId,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao invalidar tokens antigos: '.$e->getMessage(), [
                'user_id' => $userId,
            ]);
        }
    }
}

// Invalidate Password Reset Tokens - Invalida tokens de reset de senha
if (! function_exists('invalidatePasswordResetTokens')) {
    /**
     * Invalida todos os tokens de reset de senha para um email específico.
     */
    function invalidatePasswordResetTokens(string $email): void
    {
        try {
            $deleted = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            if ($deleted > 0) {
                \Illuminate\Support\Facades\Log::info('Tokens de reset de senha invalidados', [
                    'email' => $email,
                    'tokens_deleted' => $deleted,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Erro ao invalidar tokens de reset: '.$e->getMessage(), [
                'email' => $email,
            ]);
        }
    }
}
