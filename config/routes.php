<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\Task\TaskController;
use App\Controllers\User\UserController;
use App\Controllers\Welcome;
use App\Core\Router;

$router = new Router();

$router->registerRoute('GET', '/', [Welcome::class, 'index']);

$router->registerRoute('GET', '/tasks', [TaskController::class, 'index']);
$router->registerRoute('GET', '/tasks/:id', [TaskController::class, 'show']);
$router->registerRoute('POST', '/tasks', [TaskController::class, 'store'])->auth();

$router->registerRoute('GET', '/users', [UserController::class, 'index'])->auth();
$router->registerRoute('GET', '/users/:id', [UserController::class, 'show'])->auth();
$router->registerRoute('POST', '/users', [UserController::class, 'show'])->auth();
$router->registerRoute('PUT', '/users/:id', [UserController::class, 'show'])->auth();
$router->registerRoute('DELETE', '/users/:id', [UserController::class, 'show'])->auth();

$router->registerRoute('POST', '/login', [AuthController::class, 'login']);
$router->registerRoute('POST', '/logout', [AuthController::class, 'login'])->auth();
$router->registerRoute('POST', '/register', [AuthController::class, 'register']);

return $router;
