<?php

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
