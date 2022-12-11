<?php

namespace App\Core\Utils;

class Phone
{
    /**
     * Adiciona DDI brasileiro a um telefone
     * e retorna o número sem máscara
     *
     * @param string $phone
     * @return string
     */
    public static function addBrDDINoMask(string $phone): string
    {
        $phone = Str::removeMascaras($phone);
        if (strpos($phone, '+55') !== false) {
            return $phone;
        }

        return "+55$phone";
    }
}
