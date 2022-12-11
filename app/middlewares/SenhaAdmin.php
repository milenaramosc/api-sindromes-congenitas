<?php

namespace App\Middlewares;

use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Response\ResponseHandler as RBMMensagens;
use App\Model\Coban;

final class SenhaAdmin implements Middleware
{
    public function handle(RequestHandler $request): void
    {
        $coban = new Coban();
        $json = $request->getObj();

        if (empty($json->CPFCNPJ) || empty($json->SENHA)) {
            RBMMensagens::printJson('E304-005', 401);
        }

        if (!$coban->checkSenha($json->CPFCNPJ, md5($json->SENHA))) {
            RBMMensagens::printJson('E304-006', 401);
        }
    }
}
