<?php

namespace App\Core\Handlers\Middlewares;

use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Router\Route;

class MiddlewareRunner
{
    /**
     * @param array $middlewares coleção de middlewares
     * @return void
     */
    private function validate(array $middlewares): void
    {
        $request = RequestHandler::getInstance();

        /**
         * @var MiddlewareInterface $middleware
         */
        foreach ($middlewares as $middleware) {
            $middleware->handle($request);
        }
    }

    public function before(Route $route): void
    {
        $this->validate($route->before);
    }

    public function after(Route $route): void
    {
        $this->validate($route->after);
    }
}
