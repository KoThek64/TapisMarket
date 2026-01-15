<?= $this->extend('layouts/with_sidebar') ?>

<?= $this->section('sidebar') ?>

<?= view('partials/sidebar/panel', [
    'side_bar_title' => 'Section client',
    'section' => [
        'accent' => 'My',
        'non_accent' => 'Market'
    ],
    'items' => [
        [
            'item' => [
                'name' => 'Tableau de bord',
                'url' => 'client/dashboard',
                'segment' => 'dashboard',
                'icon_name' => "dashboard",
            ]
        ],
        [
            'item' => [
                'name' => 'Mes Commandes',
                'url' => 'client/orders',
                'segment' => 'orders',
                'icon_name' => "order",
            ]
        ],
        [
            'item' => [
                'name' => 'Mes Avis',
                'url' => 'client/reviews',
                'segment' => 'reviews',
                'icon_name' => "reviews",
            ]
        ],
        [
            'item' => [
                'name' => 'Mon Profil',
                'url' => 'client/profile',
                'segment' => 'profile',
                'icon_name' => "profile",
            ]
        ],
    ]
]) ?>

<?= $this->endSection() ?>

