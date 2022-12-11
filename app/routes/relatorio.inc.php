<?php

use App\Controller\RelatorioController;
use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Router\Router;

$router = Router::getInstance();

$router->get('/gerar/relatorio', fn (Request $request) => (new RelatorioController())->gerar());
