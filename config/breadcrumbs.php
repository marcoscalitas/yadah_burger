<?php

return [

    /*
     * Breadcrumbs config
     *
     * Cada chave identifica uma "trail" que podes requisitar com getBreadcrumb('chave').
     * Cada item pode ter:
     *  - 'label' (obrigatório)
     *  - 'route' (opcional) => nome da rota (ex: 'admin.users.index')
     *  - 'route_params' (opcional) => array de parâmetros para a rota
     *
     * O helper irá transformar 'route' + 'route_params' em 'url' no runtime.
     */

    // Dashboard
    'admin.index' => [
        'title' => 'Dashboard',
        'items' => [],
    ],

    // Utilizadores
    'admin.users.index' => [
        'title' => 'Utilizadores',
        'items' => [
            ['label' => 'Utilizadores', 'route' => 'admin.users.index'],
        ],
    ],

    'admin.users.create' => [
        'title' => 'Adicionar Utilizador',
        'items' => [
            ['label' => 'Utilizadores', 'route' => 'admin.users.index'],
            ['label' => 'Adicionar Utilizador', 'route' => 'admin.users.create'],
        ],
    ],

    'admin.users.edit' => [
        'title' => 'Editar Utilizador',
        'items' => [
            ['label' => 'Utilizadores', 'route' => 'admin.users.index'],
            // O nome do utilizador e editar serão adicionados dinamicamente na view
        ],
    ],

    'admin.users.show' => [
        'title' => 'Detalhes do Utilizador',
        'items' => [
            ['label' => 'Utilizadores', 'route' => 'admin.users.index'],
            // O nome do utilizador será adicionado dinamicamente na view
        ],
    ],

    // Produtos
    'admin.products.index' => [
        'title' => 'Produtos',
        'items' => [
            ['label' => 'Produtos', 'route' => 'admin.products.index'],
        ],
    ],

    'admin.products.create' => [
        'title' => 'Adicionar Produto',
        'items' => [
            ['label' => 'Produtos', 'route' => 'admin.products.index'],
            ['label' => 'Adicionar Produto', 'route' => 'admin.products.create'],
        ],
    ],

    // Categorias
    'admin.categories.index' => [
        'title' => 'Categorias',
        'items' => [
            ['label' => 'Categorias', 'route' => 'admin.categories.index'],
        ],
    ],

    'admin.categories.create' => [
        'title' => 'Adicionar Categoria',
        'items' => [
            ['label' => 'Categorias', 'route' => 'admin.categories.index'],
            ['label' => 'Adicionar Categoria', 'route' => 'admin.categories.create'],
        ],
    ],

    'admin.categories.edit' => [
        'title' => 'Editar Categoria',
        'items' => [
            ['label' => 'Categorias', 'route' => 'admin.categories.index'],
            // O nome da categoria e editar serão adicionados dinamicamente na view
        ],
    ],

    // Pedidos
    'admin.orders.index' => [
        'title' => 'Pedidos',
        'items' => [
            ['label' => 'Pedidos', 'route' => 'admin.orders.index'],
        ],
    ],

    'admin.orders.create' => [
        'title' => 'Adicionar Pedido',
        'items' => [
            ['label' => 'Pedidos', 'route' => 'admin.orders.index'],
            ['label' => 'Adicionar Pedido', 'route' => 'admin.orders.create'],
        ],
    ],

    // Perfil
    'admin.profile.index' => [
        'title' => 'Meu Perfil',
        'items' => [
            ['label' => 'Meu Perfil', 'route' => 'admin.profile.index'],
        ],
    ],

    'admin.profile.edit' => [
        'title' => 'Editar Perfil',
        'items' => [
            ['label' => 'Meu Perfil', 'route' => 'admin.profile.index'],
            ['label' => 'Editar Perfil', 'route' => 'admin.profile.edit'],
        ],
    ],

    // Definições
    'admin.settings.index' => [
        'title' => 'Definições',
        'items' => [
            ['label' => 'Definições', 'route' => 'admin.settings.index'],
        ],
    ],

    'admin.settings.change.password' => [
        'title' => 'Alterar Senha',
        'items' => [
            ['label' => 'Definições', 'route' => 'admin.settings.index'],
            ['label' => 'Alterar Senha', 'route' => 'admin.settings.change.password'],
        ],
    ],

    'admin.settings.change.email' => [
        'title' => 'Alterar E-mail',
        'items' => [
            ['label' => 'Definições', 'route' => 'admin.settings.index'],
            ['label' => 'Alterar E-mail', 'route' => 'admin.settings.change.email'],
        ],
    ],
];
