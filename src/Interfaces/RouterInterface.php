<?php

namespace App\Interfaces;

use App\Core\Router;
use App\Helpers\Http\Method;

interface RouterInterface {
    public function registerRoute(Method $method, string $url, array $handler): Router;

    public function getRoute($method): ?array;
}