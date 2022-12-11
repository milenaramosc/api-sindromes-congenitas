<?php

namespace App\Middlewares;

use App\Core\Handlers\Exceptions\ExceptionHandler;
use App\Core\Handlers\Middlewares\Middleware;
use App\Core\Handlers\Request\RequestHandler;
use Modules\ContaBacen\LoggedInAccount;

/**
 * Prepara a conta de um cliente logado para recebimento de crédito
 *
 * @category Middleware
 * @package  Middleware
 * @author   Jonas Vicente <jonas.vicente@rbmweb.com.br>
 * @license  Proprietary
 * @link     https://gitlab.com/rbmweb/rbmdocs/api_conta_digital_docs
 */
class LoggedInAccountMiddleware implements Middleware
{
    /**
     * Valida e prepara a conta do cliente para
     * recebimento de crédito
     *
     * @param RequestHandler $request Description
     *
     * @return void
     */
    public function handle(RequestHandler $request): void
    {
        try {
            $json = $request->getObj();

            LoggedInAccount::instance()->set(
                $json->CPFCNPJ ?? "",
                $json->ID_CONTA ?? 0
            );
        } catch (\Throwable $th) {
            (new ExceptionHandler($th, 'E304-014'))->print();
        }
    }
}
