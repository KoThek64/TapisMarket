<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
Les gros pavés commenté sont des méthodes de routage testé
et approuvé par Niels, je les laisses ici au cas où les nouvelles 
méthodes ne fonctionnent pas. Voici ce que cette nouvelle méthode
de routage implique :

L'utilisation de $routes->resource() implique que vos contrôleurs 
doivent suivre les conventions de nommage de CodeIgniter 4 
(méthodes index, show, new, create, edit, update, delete) 
et que vos formulaires d'édition/suppression doivent 
utiliser le "method spoofing" (champ caché _method avec valeur 
PUT ou DELETE) si vous n'utilisez pas l'option websafe.
*/

// Public
$routes->get('/', 'Home::index');

// Catalog (Public)
$routes->get('catalog', 'Catalog::index');
$routes->get('product/(:num)', 'Catalog::detail/$1');
$routes->get('search', 'Catalog::search');

// Cart (Public)
$routes->group('cart', function ($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add', 'Cart::add');
    $routes->get('remove/(:num)', 'Cart::remove/$1');
    $routes->post('update', 'Cart::update');
    $routes->get('clear', 'Cart::clear');
});

// Auth
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
});

// Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'admin'], function ($routes) {
    
    $routes->get('/', 'Dashboard::index');

    /*
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'Orders::index'); 
        $routes->get('detail/(:num)', 'Orders::detail/$1');
    });
    */
    $routes->resource('orders', ['only' => ['index', 'show']]);

    /*
    $routes->group('categories', function ($routes) {
        $routes->get('/', 'Categories::index');
        $routes->get('create', 'Categories::new');
        $routes->post('save', 'Categories::save');
        $routes->get('edit/(:num)', 'Categories::edit/$1');
        $routes->post('update/(:num)', 'Categories::save/$1');
        $routes->post('delete/(:num)', 'Categories::delete/$1');
    });
    */
    $routes->resource('categories');

    /*
    $routes->group('products', function ($routes) {
        $routes->get('/', 'Products::index');
        $routes->get('approve/(:num)', 'Products::approve/$1');
        $routes->match(['GET', 'POST'], 'reject/(:num)', 'Products::reject/$1');
        $routes->post('delete/(:num)', 'Products::delete/$1');
    });
    */
    $routes->get('products/approve/(:num)', 'Products::approve/$1');
    $routes->match(['GET', 'POST'], 'products/reject/(:num)', 'Products::reject/$1');
    $routes->resource('products');

    /*
    $routes->group('users', function ($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('approveSeller/(:num)', 'Users::approveSeller/$1'); 
        $routes->match(['GET', 'POST'], 'refuseSeller/(:num)', 'Users::refuseSeller/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });
    */
    $routes->get('users/approve/(:num)', 'Users::approveVendor/$1'); 
    $routes->match(['GET', 'POST'], 'users/reject/(:num)', 'Users::rejectVendor/$1');
    $routes->resource('users', ['only' => ['index', 'delete']]);

    /*
    $routes->group('reviews', function ($routes) {
        $routes->get('/', 'Reviews::index');
        $routes->get('status/(:num)/(:segment)', 'Reviews::changeStatus/$1/$2');
        $routes->post('delete/(:num)', 'Reviews::delete/$1');
    }); 
    */
    $routes->get('reviews/status/(:num)/(:segment)', 'Reviews::changeStatus/$1/$2');
    $routes->resource('reviews', ['only' => ['index', 'delete']]);

});

// Client
$routes->group('client', ['namespace' => 'App\Controllers\Client', 'filter' => 'client'], function ($routes) {

    $routes->get('/', 'Dashboard::index');

    $routes->group('profile', function($routes) {
        $routes->get('/', 'Profile::index');
        $routes->post('update', 'Profile::update');
    });

    /*
    $routes->group('addresses', function($routes) {
        $routes->get('/', 'Addresses::index');
        $routes->get('create', 'Addresses::create');
        $routes->post('save', 'Addresses::store');
        $routes->get('edit/(:num)', 'Addresses::edit/$1');
        $routes->post('update/(:num)', 'Addresses::update/$1');
        $routes->post('delete/(:num)', 'Addresses::delete/$1');
    });
    */
    $routes->resource('addresses');

    /*
    $routes->group('orders', function($routes) {
        $routes->get('/', 'Orders::index');
        $routes->get('detail/(:num)', 'Orders::detail/$1');
    });
    */

    $routes->resource('orders', ['only' => ['index', 'show']]);
    
    $routes->group('reviews', function($routes) {
        $routes->get('create/(:num)', 'Reviews::create/$1');
        $routes->post('save', 'Reviews::store');
    });
});

// Seller
$routes->group('seller', ['namespace' => 'App\Controllers\Seller'], function ($routes) {
    
    $routes->get('/', 'Dashboard::index');

    /*
    $routes->group('products', function($routes) {
        $routes->get('/', 'Products::index');
        $routes->get('create', 'Products::create');
        $routes->post('save', 'Products::store');
        $routes->get('edit/(:num)', 'Products::edit/$1');
        $routes->post('update/(:num)', 'Products::update/$1');
        $routes->post('delete/(:num)', 'Products::delete/$1');
    });
    */
    $routes->resource('products');

    /*
    $routes->group('orders', function($routes) {
        $routes->get('/', 'Orders::index');
        $routes->get('detail/(:num)', 'Orders::detail/$1');
        $routes->post('ship/(:num)', 'Orders::ship/$1');
    });
    */
    $routes->post('orders/ship/(:num)', 'Orders::ship/$1');
    $routes->resource('orders', ['only' => ['index', 'show']]);

    
    $routes->group('shop', function($routes) {
        $routes->get('/', 'Shop::index');
        $routes->get('edit', 'Shop::edit');
        $routes->post('update', 'Shop::update');
    });
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
