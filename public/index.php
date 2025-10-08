<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Http\Request;

try {
    $config = include __DIR__ . '/../config/config.php';
    $router = include __DIR__ . '/../config/routes.php';
    $app = new App();
    $request = new Request();

    $app->initDB($config) // configure db
        ->initRouter($router) // configure router
        ->prepare($request) // handle uri and check auth if needed
        ->runController($request);
} catch (\Exception $e) {
    echo "<pre>Exception message:\n\t{$e->getMessage()}</pre>";
}
