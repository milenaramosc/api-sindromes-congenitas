<?php

namespace App\Core\Handlers\Router;

use App\Core\Handlers\Middlewares\Middleware;
use Closure;

class Route
{
    public Closure $callback;
    public array $pathParams = [];
    public array $before = [];
    public array $after = [];

    public function __construct(Closure $callback, array $pathParams)
    {
        $this->callback = $callback;
        $this->pathParams = $pathParams;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function before(Middleware $middleware)
    {
        $this->before[] = $middleware;
        return $this;
    }

    public function after(Middleware $middleware)
    {
        $this->after[] = $middleware;
        return $this;
    }
}
