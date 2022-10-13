<?php

namespace App\Core\Exceptions;

use Exception;

/**
 * Exceptions de regras de negócio do projeto.
 * Utilize para validações de entrada do cliente e validações de regras de
 * negócio que necessitem dar uma mensagem de retorno ao cliente.
 *
 * A mensagem desta exception será exibida para o cliente.
 */
final class RBMException extends Exception
{
}
