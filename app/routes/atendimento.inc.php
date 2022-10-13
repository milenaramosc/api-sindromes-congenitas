<?php

use App\Controller\AtendimentoController;
use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Router\Router;

$router = Router::getInstance();

$router->post('/atendimento/iniciar', fn (Request $request) => (new AtendimentoController())->iniciar($request->getJson()));
