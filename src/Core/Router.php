<?php

namespace App\Core;

use App\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private $routerContainer = [];

    private $lastIndex = null;

    public function registerRoute(string $method, string $url, array $handler): Router
    {
        $urlWithReplacedPlaceholder = $this->matchRoute($url, $_SERVER['REQUEST_URI']);

        if (null !== $urlWithReplacedPlaceholder) {
            $url = $urlWithReplacedPlaceholder;
        }

        if (isset($this->routerContainer[$method][$url])) {
            throw new \Exception(sprintf('Route [%s] %s already registered!', $method, $url));
        }
        $this->routerContainer[$method][$url] = [
            [
                'controller' => $handler[0],
                'method' => $handler[1]
            ], ...['auth' => false]
        ];
        $this->lastIndex = ['method' => $method, 'url' => $url];

        return $this;
    }

    public function auth(): Router
    {
        $method = $this->lastIndex['method'];
        $url = $this->lastIndex['url'];
        $this->routerContainer[$method][$url]['auth'] = true;

        return $this;
    }

    public function getRoute($method, $uri): ?array
    {
        return $this->routerContainer[$method][$uri] ?? null;
    }

    private function matchRoute(string $pattern, string $uri): ?string
    {
        $regex = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $pattern);
        $regex = "#^" . $regex . "$#";

        if (preg_match($regex, $uri, $matches)) {
            return str_replace($pattern, $uri, $pattern);
        }

        return null;
    }
}