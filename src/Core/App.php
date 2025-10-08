<?php

namespace App\Core;

use App\Core\Container\UserContainer;
use App\Core\Http\Middleware;
use App\Core\Http\Request;
use App\Interfaces\RouterInterface;

class App
{
    protected array $config;

    private array $handler;

    public function initDB(array $config): App
    {
        $db = new Database();
        $db->setConnection($config);
        $this->config['db'] = $db->getConnection();

        return $this;
    }

    public function initRouter(RouterInterface $router): App
    {
        $this->config['router'] = $router;

        return $this;
    }

    public function prepare(Request $request): App
    {
        // Define handler for uri
        $this->handler = $this->config['router']->getRoute($request->method, $request->uri);
        if (null === $this->handler)
        {
            ResponseCode::error(404);
        }

        // Fetching user
        $user = Middleware::checkAuth($this->handler['auth'], $request->headers, $this->config['db']);
        if (-1 === $user) {
            ResponseCode::error(401);
        }

        // Adding user's id to container if user exists
        if (null != $user) {
            UserContainer::setUserId($user);
        }

        return $this;
    }

    public function runController(Request $request): void
    {
        $controllerClass = new $this->handler['controller'](
            $this->config['db'],
            $request
        );
        $method = $this->handler['method'];

        call_user_func_array([$controllerClass, $method], $this->handler['params']);
    }

}