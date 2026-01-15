<?= $this->extend('layouts/with_sidebar') ?>

<?= $this->section('sidebar') ?>

<?= view('partials/sidebar/panel', [
    'side_bar_title' => 'Section vendeur',
    'section' => [
        'accent' => 'Seller',
        'non_accent' => 'Market'
    ],
    'items' => [
        [
            'item' => [
                'name' => 'Tableau de bord',
                'url' => 'seller',
                'segment' => '',
                'icon_name' => "dashboard",
            ]
        ],
        [
            'item' => [
                'name' => 'Mes Produits',
                'url' => 'seller/products',
                'segment' => 'products',
                'icon_name' => "product",
            ]
        ],
        [
            'item' => [
                'name' => 'Commandes',
                'url' => 'seller/orders',
                'segment' => 'orders',
                'icon_name' => "order",
            ]
        ],
        [
            'item' => [
                'name' => 'Ma Boutique',
                'url' => 'seller/shop',
                'segment' => 'shop',
                'icon_name' => "profile",
            ]
        ],
        [
            'item' => [
                'name' => 'Avis Clients',
                'url' => 'seller/reviews',
                'segment' => 'reviews',
                'icon_name' => "reviews",
            ]
        ],
    ]
]) ?>

<?= $this->endSection() ?>

