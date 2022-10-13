<?php

namespace App\Core\Utils;

use App\Core\Exceptions\RBMException;

class EmailAddress
{
    /**
     * @param string $email
     * @return void
     */
    public static function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RBMException("A chave informada não é do tipo e-mail");
        }
    }
}
