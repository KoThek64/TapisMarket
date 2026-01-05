<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

//Publique
$routes->get('/', 'Home::index');

$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    $routes->get('logout', 'Auth::logout');
});

//Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    $routes->group('orders', function ($routes) {
        $routes->get('/', 'Orders::index'); 
        $routes->get('detail/(:num)', 'Orders::detail/$1');
    });

    $routes->group('categories', function ($routes) {
        $routes->get('/', 'Categories::index');
        $routes->get('create', 'Categories::new');
        $routes->post('save', 'Categories::save');
        $routes->get('edit/(:num)', 'Categories::edit/$1');
        $routes->get('delete/(:num)', 'Categories::delete/$1');
    });

    $routes->group('products', function ($routes) {
        $routes->get('/', 'Products::index');
        $routes->get('approve/(:num)', 'Products::approve/$1');
        $routes->match(['GET', 'POST'], 'reject/(:num)', 'Products::reject/$1');
        $routes->get('delete/(:num)', 'Products::delete/$1');
    });

   $routes->group('users', function ($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('approveSeller/(:num)', 'Users::approveSeller/$1'); 
        $routes->match(['GET', 'POST'], 'refuseSeller/(:num)', 'Users::refuseSeller/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });

    $routes->group('reviews', function ($routes) {
        $routes->get('/', 'Reviews::index');
        $routes->get('status/(:num)/(:segment)', 'Reviews::changeStatus/$1/$2');
        $routes->get('delete/(:num)', 'Reviews::delete/$1');
    });
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
