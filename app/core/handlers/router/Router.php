<?php

namespace App\Core\Handlers\Router;

use App\Core\Handlers\Request\RequestHandler;
use App\Core\Utils\Str;
use Closure;

class Router
{
    private static array $routes;
    private static ?self $instance = null;
    private static RequestHandler $request;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        self::$instance ??= new Router();
        self::$request = RequestHandler::getInstance();
        return self::$instance;
    }

    public function getRoutes(bool $withPathParams): array
    {
        return self::$routes[self::$request->getMethod()][$withPathParams] ?? [];
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas GET do sistema
     * @param Closure $callback
     * @return Route
     */
    public function get(string $url, Closure $callback): Route
    {
        return $this->addRoute($url, $callback, 'GET');
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas POST do sistema
     * @param Closure $callback
     * @return Route
     */
    public function post(string $url, Closure $callback): Route
    {
        return $this->addRoute($url, $callback, 'POST');
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas PUT do sistema
     * @param Closure $callback
     * @return Route
     */
    public function put(string $url, Closure $callback): Route
    {
        return $this->addRoute($url, $callback, 'PUT');
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas DELETE do sistema
     * @param Closure $callback
     * @return Route
     */
    public function delete(string $url, Closure $callback): Route
    {
        return $this->addRoute($url, $callback, 'DELETE');
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas PATCH do sistema
     * @param Closure $callback
     * @return Route
     */
    public function patch(string $url, Closure $callback): Route
    {
        return $this->addRoute($url, $callback, 'PATCH');
    }

    /**
     * @author Jonas Vicente
     * @param string $url Endpoint para ser adicionado às rotas
     * @param Closure $callback
     * @return Route
     */
    public function any($url, $callback)
    {
        switch (self::$request->getMethod()) {
            case 'GET':
                return $this->get($url, $callback);
            case 'POST':
                return $this->post($url, $callback);
            case 'PUT':
                return $this->put($url, $callback);
            case 'DELETE':
                return $this->delete($url, $callback);
            case 'PATCH':
                return $this->patch($url, $callback);
        }
    }

    /**
     * Registra as rotas no sistema
     * @author Jonas Vicente
     * @param string $url
     * @param Closure $callback
     * @param string $method
     * @return Route
     */
    private function addRoute(string $url, Closure $callback, string $method): Route
    {
        $url = Str::setUrlPattern($url);

        $pathParams = self::$request->getPathParams($url);
        return self::$routes[$method][$pathParams !== []][$url] = new Route($callback, $pathParams);
    }
}
