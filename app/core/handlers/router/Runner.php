<?php

namespace App\Core\Handlers\Router;

use App\Core\Handlers\Middlewares\MiddlewareRunner;
use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Response\ResponseHandler;
use App\Core\Utils\Str;

class Runner
{
    private string $url;
    private Router $router;
    private Request $request;
    private array $urlParts;

    public function __construct()
    {
        $this->request  = Request::getInstance();
        $this->router   = Router::getInstance();
        $this->url      = Str::setUrlPattern($this->request->getUrl());
        $this->urlParts = explode("/", $this->url);
    }

    /**
     * Executa o projeto
     * @author Jonas Vicente
     * @return void
     */
    public function run(): void
    {
        $middleware = new MiddlewareRunner();

        $route  = $this->getRoute();
        $params = [];

        if ($route->pathParams !== []) {
            foreach (array_keys($route->pathParams) as $key) {
                $params[] = $this->urlParts[$key];
            }
        }

        $params[] = $this->request;

        $middleware->before($route);
        echo call_user_func_array($route->getCallback(), $params);
        $middleware->after($route);
    }

    /**
     * @author Jonas Vicente
     * @return Route
     */
    private function getRoute(): Route
    {
        /**
         * Rotas sem path parameter
         * @var ?Route $route
         */
        $route = @$this->router->getRoutes(false)[$this->url];
        if ($route !== null) {
            return $route;
        }

        /**
         * Nome da rota declarada
         * @var string $routeName
         *
         * Objeto da rota declarada
         * @var Route $route
         */
        foreach (@$this->router->getRoutes(true) as $routeName => $route) {
            $routeParts = explode('/', $routeName);

            if (count($this->urlParts) === count($routeParts)) {
                $urlParts = $this->urlParts;

                foreach (array_keys($route->pathParams) as $key) {
                    unset($urlParts[$key]);
                    unset($routeParts[$key]);
                }

                if ($urlParts === $routeParts) {
                    return $route;
                }
            }
        }

        ResponseHandler::printJson('E000-001', 404);
    }
}
