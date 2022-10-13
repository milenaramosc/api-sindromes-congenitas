<?php

use App\Controller\QuestionarioController;
use App\Core\Handlers\Request\RequestHandler as Request;
use App\Core\Handlers\Router\Router;

$router = Router::getInstance();

$router->post('/questionario/inserir', fn (Request $request) => (new QuestionarioController())->enviarDadosQuestionario($request->getJson()));
