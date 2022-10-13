<?php

use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Router\Router;
use App\Core\Utils\Cep;

$router = Router::getInstance();

$router->get('/cep/buscar', fn (Request $request) => Cep::consultaCep($request->getJson()));
