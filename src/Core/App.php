<?php

namespace App\Core;

use App\Core\Http\Request;
use App\Interfaces\RouterInterface;

class App
{
    protected array $config;

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

    /**
     * TODO: REFACTOR
     */
    public function listen(Request $request): void
    {
        $handler = $this->config['router']->getRoute($request->method, $request->uri);

        // REFACTOR
        if (null === $handler) {
            http_response_code(404);
            exit();
        }
        // REFACTOR

        $controllerClass = new $handler[0]['controller'](
            $this->config['db']
        );
        $method = $handler[0]['method'];

        call_user_func_array([$controllerClass, $method], $handler['params']);
    }

}