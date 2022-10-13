<?php

namespace App\Middlewares;

use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Response\ResponseHandler as Response;
use App\Model\Coban;

final class ListaClientesAdmin implements Middleware
{
    public function handle(RequestHandler $request): void
    {
        $coban = new Coban();
        $json = $request->getObj();
        if ($coban->cantAccessClientsList($json->CPFCNPJ)) {
            Response::printJson('E304-012', 403);
        }
    }
}
