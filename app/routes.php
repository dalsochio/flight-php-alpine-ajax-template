<?php

// initialize the Flight router for defining application routes
use App\Controllers\HomeController;

$router = Flight::router();

Flight::group('', function () use ($router) {
    $router->get('/', function () {
        Flight::redirect('/main');
    });

    $router->get('/main', [HomeController::class, 'main']);
    $router->get('/main-aside', [HomeController::class, 'mainAside']);
    $router->get('/main-aside-navbar', [HomeController::class, 'mainAsideNavbar']);
    $router->get('/morph-demo', [HomeController::class, 'morphDemo']);

});
