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
        if (! isAdmin()) return abort(403, "Acesso negado. Apenas administradores podem {$crudAction} {$entity}.");
    }
}
