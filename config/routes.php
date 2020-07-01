<?php

use Slim\Routing\RouteCollectorProxy;

use App\Controllers\UserController;
use App\Controllers\MateriaContoller;

use App\Middlewares\LoginMiddleware;
use App\Middlewares\SignUpMiddleware;
use App\Middlewares\AddMateriaMiddleware;
use App\Middlewares\TokenMiddleware;


return function ($app) {
    $app->post('/usuario',UserController::class.':registro')->add(SignUpMiddleware::class);
    $app->post('/login',UserController::class.':login')->add(LoginMiddleware::class);

    $app->group('/materias', function (RouteCollectorProxy $group) {
        $group->post('[/]', MateriaContoller::class . ':agregar')->add(AddMateriaMiddleware::class);
        $group->get('/{:id}',MateriaContoller::class.':show');
        $group->put('/{:id}/{:profesor}',MateriaContoller::class.':asigProf');
        $group->put('/{:id}',MateriaContoller::class.':anotarse');
        $group->get('[/]',MateriaContoller::class.':lista');

    })->add(TokenMiddleware::class);
};
