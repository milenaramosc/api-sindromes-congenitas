<?php

namespace App\Core\Exceptions;

use RuntimeException;

/**
 * Exceptions de banco de dados.
 * Utilize para estourar exceptions de banco de dados, por exemplo, na execução
 * de uma query.
 *
 * A mensage desta exception não deve ser exibida para o cliente.
 */
final class DbException extends RuntimeException
{
}
