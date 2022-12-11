<?php

namespace App\Middlewares;

use App\Core\Handlers\Exceptions\ExceptionHandler;
use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Response\ResponseHandler as Response;
use App\Model\ContaBacen;
use Modules\ContaBacen\SenhaContaBacen as SenhaContaBacenEntity;

final class SenhaContaBacen implements Middleware
{
    public function handle(RequestHandler $request): void
    {
        try {
            $contaBacen = new ContaBacen();
            $json = $request->getObj();

            if (empty($json->CPFCNPJ) || empty($json->CONTA_PAGAMENTO) || empty($json->SENHA_CONTA)) {
                Response::printJson('E304-007', 401);
            }

            $senhaContaBacen = new SenhaContaBacenEntity($json->SENHA_CONTA);
            $passwordHash = $contaBacen->passwordHash($json->CPFCNPJ, $json->CONTA_PAGAMENTO);

            if ($senhaContaBacen->isNotValid($passwordHash)) {
                Response::printJson('E304-009', 401);
            }
        } catch (\Throwable $th) {
            (new ExceptionHandler($th, 'E304-008'))->print();
        }
    }
}
