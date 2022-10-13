<?php

namespace App\Core\Exceptions;

use Exception;

/**
 * Exceptions de services.
 * Utilize quando houver algum erros em requests no parceiro.
 *
 * A mensagem desta exception não deve ser retornada diretamente ao cliente.
 */
class ServiceException extends Exception
{
}
