<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;
use App\Controllers\Welcome;
use App\Core\Router;
use App\Helpers\Http\Method;

$router = new Router();

$router->registerRoute(Method::get, '/', [Welcome::class, 'index']);

$router->registerRoute(Method::get, '/users', [UserController::class, 'index'])->auth();
$router->registerRoute(Method::get, '/users/:id', [UserController::class, 'show'])->auth();
$router->registerRoute(Method::put, '/users/:id', [UserController::class, 'update'])->auth();
$router->registerRoute(Method::delete, '/users/:id', [UserController::class, 'delete'])->auth();

$router->registerRoute(Method::post, '/login', [AuthController::class, 'login']);
$router->registerRoute(Method::post, '/logout', [AuthController::class, 'logout'])->auth();
$router->registerRoute(Method::post, '/register', [AuthController::class, 'register']);

return $router;
