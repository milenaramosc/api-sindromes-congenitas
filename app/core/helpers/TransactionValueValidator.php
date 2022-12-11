<?php

namespace App\Core\Helpers;

use App\Core\Exceptions\RBMException;
use App\Core\Utils\Money;

/**
 * Validador de valor para transações
 *
 * @category Helpers
 * @package  Helpers
 * @author   Jonas Vicente <jonas.vicente@rbmweb.com.br>
 * @license  Proprietary
 * @link     https://gitlab.com/rbmweb/rbmdocs/api_conta_digital_docs
 */
class TransactionValueValidator
{
    /**
     * Valida e formata um valor para transacionar
     *
     * @param string|float|int $value Valor para transacionar
     *
     * @return float
     */
    public static function prepare($value): float
    {
        if (empty($value)) {
            throw new RBMException("Valor inválido", 1);
        }

        $value = Money::moedaBDTWO((string) $value);
        if ($value <= 0) {
            throw new RBMException("Valor inválido", 2);
        }

        return (float) round($value, 2);
    }
}
