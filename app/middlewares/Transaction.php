<?php

namespace App\Middlewares;

use App\Core\Handlers\Exceptions\ExceptionHandler;
use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use App\Core\Handlers\Response\ResponseHandler as Response;
use Modules\ContaBacen\LoggedInAccount;
use Modules\ContaBacen\SenhaContaBacen;

/**
 * Middleware para rotas de transação
 *
 * @category Middleware
 * @package  App\Middlewares
 * @author   Jonas Vicente <jonas.vicente@rbmweb.com.br>
 * @license  Proprietary
 * @link     https://gitlab.com/rbmweb/rbmdocs/api_conta_digital_docs
 */
final class Transaction implements Middleware
{
    /**
     * Inicia a LoggedInAccount e valida sua senha
     *
     * @param RequestHandler $request Requisição
     *
     * @return void
     */
    public function handle(RequestHandler $request): void
    {
        try {
            $json = $request->getObj();
            $senhaContaBacen = new SenhaContaBacen($json->SENHA_CONTA);
            $conta = LoggedInAccount::instance();

            $conta->set($json->CPFCNPJ, (int) $json->ID_CONTA);
            if ($senhaContaBacen->isNotValid($conta->hashSenha())) {
                Response::printJson('E304-011', 401);
            }
        } catch (\Throwable $th) {
            (new ExceptionHandler($th, 'E304-010'))->print();
        }
    }
}
