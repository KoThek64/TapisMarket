<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
Voici ce que cette nouvelle méthode de routage implique :

L'utilisation de $routes->resource() implique que nos contrôleurs 
doivent suivre les conventions de nommage de CodeIgniter 4 
(méthodes index, show, new, create, edit, update, delete) 
et que nos formulaires d'édition/suppression doivent 
utiliser le "method spoofing" (champ caché _method avec valeur 
PUT ou DELETE) si on n'utilise pas l'option websafe.
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

//Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth:admin'], function ($routes) {
    
    $routes->get('/', 'Dashboard::index');

    $routes->resource('orders', ['only' => ['index', 'show']]);

    $routes->resource('categories', ['expect' => ['show']]);

    $routes->resource('products', ['only' => ['index', 'delete']]);
    $routes->get('products/approve/(:num)', 'Products::approve/$1');
    $routes->match(['GET', 'POST'], 'products/reject/(:num)', 'Products::reject/$1');
    
    $routes->resource('users', ['only' => ['index', 'delete']]);
    $routes->get('users/approve/(:num)', 'Users::approveSeller/$1'); 
    $routes->match(['GET', 'POST'], 'users/reject/(:num)', 'Users::rejectSeller/$1');

    $routes->resource('reviews', ['only' => ['index', 'delete']]);
    $routes->get('reviews/status/(:num)/(:segment)', 'Reviews::changeStatus/$1/$2');
});

// Client
$routes->group('client', ['namespace' => 'App\Controllers\Client', 'filter' => 'auth:client'], function ($routes) {

    $routes->get('/', 'Dashboard::index');

    $routes->group('profile', function($routes) {
        $routes->get('/', 'Profile::index');
        $routes->post('update', 'Profile::update');
    });

    $routes->resource('addresses');

    $routes->resource('orders', ['only' => ['index', 'show']]);
    
    $routes->group('reviews', function($routes) {
        $routes->get('create/(:num)', 'Reviews::create/$1');
        $routes->post('save', 'Reviews::store');
    });
});

// Seller
$routes->group('seller', ['namespace' => 'App\Controllers\Seller', 'filter' => 'auth:seller'], function ($routes) {
    
    $routes->get('/', 'Dashboard::index');

    $routes->resource('products');

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
