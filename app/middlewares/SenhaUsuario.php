<?php

namespace App\Middlewares;

use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Response\ResponseHandler as RBMMensagens;
use App\Model\Usuario;

final class SenhaUsuario implements Middleware
{
    public function handle(RequestHandler $request): void
    {
        $usuario = new Usuario();
        $json = $request->getObj();

        if (empty($json->CPFCNPJ) || empty($json->SENHA)) {
            RBMMensagens::printJson('E304-015', 401);
        }

        if (!$usuario->checkSenha($json->CPFCNPJ, md5($json->SENHA))) {
            RBMMensagens::printJson('E304-003', 401);
        }
    }
}
