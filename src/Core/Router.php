<?php

namespace App\Core;

use App\Helpers\Http\Method;
use App\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private $routerContainer = [];

    private $lastIndex = null;

    private string $uri;


    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function registerRoute(Method $method, string $url, array $handler): Router
    {

        $urlWithReplacedPlaceholder = $this->matchRoute(
            $url,
            $this->uri,
        );

        // return 404 if url is not registered
        if (null === $urlWithReplacedPlaceholder) {
            ResponseCode::error(404);
        }

        $url = $urlWithReplacedPlaceholder['url'];

        // check on duplicates in router
        if (isset($this->routerContainer[$method->value][$url])) {
            throw new \Exception(sprintf('Route [%s] %s already registered!', $method->value, $url));
        }

        // configuring controller, method and setting auth default value.
        $this->routerContainer[$method->value][$url] = [
            'controller' => $handler[0],
            'method' => $handler[1],
            'auth' => false
        ];

        // fetching params for
        $this->routerContainer[$method->value][$url]['params'] = empty($urlWithReplacedPlaceholder['params'])?
            [] : $urlWithReplacedPlaceholder['params'];

        // registering last route. Later this route will get auth=true on demand
        $this->lastIndex = ['method' => $method, 'url' => $url];

        return $this;
    }

    public function auth(): Router
    {
        $method = $this->lastIndex['method']->value;
        $url = $this->lastIndex['url'];
        $this->routerContainer[$method][$url]['auth'] = true;

        return $this;
    }

    public function getRoute($method): ?array
    {
        return $this->routerContainer[$method][$this->uri] ?? null;
    }

    private function matchRoute(string $pattern, string $uri): ?array
    {
        $result = [];

        $regex = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $pattern);
        $regex = "#^" . $regex . "$#";

        if (preg_match($regex, $uri, $matches)) {
            $result['params'] =  array_filter(
                $matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY
            );
        }

        if (!preg_match($regex, $uri, $matches)) {
            return null;
        }
        $result['url'] = str_replace($pattern, $uri, $pattern);

        return $result;
    }
}