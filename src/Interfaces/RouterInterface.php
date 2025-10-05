<?php

namespace App\Interfaces;

use App\Core\Router;

interface RouterInterface {
    public function registerRoute(string $method, string $url, array $handler): Router;

    public function getRoute($method, $uri): ?array;
}