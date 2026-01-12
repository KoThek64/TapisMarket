<?= $this->extend('layouts/with_sidebar') ?>

<?= $this->section('sidebar') ?>

<?= view('partials/sidebar/panel', [
    'side_bar_title' => 'Section admin',
    'section' => [
        'accent' => 'Admin',
        'non_accent' => 'Market'
    ],
    'items' => [
        [
            'item' => [
                'name' => 'Tableau de bord',
                'url' => 'admin',
                'segment' => '',
                'icon_name' => "dashboard",
            ]
        ],
        [
            'item' => [
                'name' => 'Catégories',
                'url' => 'admin/categories',
                'segment' => 'categories',
                'icon_name' => "category",
            ]
        ],
        [
            'item' => [
                'name' => 'Produits',
                'url' => 'admin/products',
                'segment' => 'products',
                'icon_name' => "product",
                'number' => $pendingProductsCount,
            ]
        ],
        [
            'item' => [
                'name' => 'Utilisateurs',
                'url' => 'admin/users',
                'segment' => 'users',
                'icon_name' => "users",
                'number' => $pendingSellersCount,
            ]
        ],
        [
            'item' => [
                'name' => 'Avis Clients',
                'url' => 'admin/reviews',
                'segment' => 'reviews',
                'icon_name' => "reviews",
            ]
        ],
        [
            'item' => [
                'name' => 'Commandes',
                'url' => 'admin/orders',
                'segment' => 'orders',
                'icon_name' => "order",
            ]
        ],
    ]
]) ?>

<?= $this->endSection() ?>