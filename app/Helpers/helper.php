<?php

use Illuminate\Http\Request;
use App\Services\UploadService;

if (!function_exists('getBreadcrumb')) {
    /**
     * Retorna o breadcrumb configurado com as rotas resolvidas.
     *
     * @param string $key Nome da trail (ex: 'admin.users.show')
     * @param array $extraItems Itens extras (ex: [['label' => 'João Silva']])
     * @param string|null $customTitle Sobrescreve o título padrão (opcional)
     * @return array
     */
    function getBreadcrumb(string $key, array $extraItems = [], ?string $customTitle = null): array
    {
        $breadcrumbs = config('breadcrumbs');
        $config = $breadcrumbs[$key] ?? null;

        if (!$config) {
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
if (!function_exists('handlePhotoUpload')) {
    function handlePhotoUpload(Request $request, string $folder, string $field): string
    {
        try {
            $fileData = (new UploadService)->configure([
                'mimes' => ['jpg', 'jpeg', 'png'],
                'maxSizeMB' => 3,
                'folder' => $folder,
                'disk' => 'public'
            ])->upload($request->file($field));

            return $fileData['path'];
        } catch (\Throwable $e) {
            Log::error('Erro no upload da foto: ' . $e->getMessage());
            throw new \Exception('Erro ao enviar foto: ' . $e->getMessage());
        }
    }
}

// getStatusBadge
if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status): string
    {
        $badge = fn($class, $label) => "<span class=\"badge text-{$class}-500 bg-{$class}-500/15\">$label</span>";

        $badges = [
            'i'  => $badge('warning', 'Inativo'),
            'p'  => $badge('primary', 'Pendente'),
            'a'  => $badge('success', 'Ativado'),
            'sp' => $badge('warning', 'Suspenso'),
            'd'  => $badge('danger', 'Apagado'),
        ];

        return $badges[$status] ?? $badge('dark', '-');
    }
}

// Get current User
if (!function_exists('getCurrentUser')) {
    function getCurrentUser()
    {
        $user = auth('admin')->user() ?? auth()->user();
        return $user;
    }
}

// isAdmin
if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        $user = getCurrentUser();
        return $user && $user->role === 'admin';
    }
}
