<@php

use {namespace}\{controllerName};
use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Router\Router;

$router = Router::getInstance();

$router->get(
    '/index', 
    fn (Request $request) => (new {controllerName}())
        ->index($request->getObj())
);
