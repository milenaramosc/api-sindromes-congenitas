<?php

namespace App\Model;

use App\Core\Helpers\ModelHelper;

class CodigosMensageria extends ModelHelper
{
    public function __construct()
    {
    }

    public function saveCode(string $code, string $description): void
    {
        $this->runQuery(
            "INSERT INTO codigos_mensageria SET
                CODIGO     = :codigo,
                DESCRICAO  = :descricao,
                CREATED_AT = NOW()",
            [
                ":codigo" => $code,
                ":descricao" => $description
            ]
        );
    }

    public function getLastCode(): string
    {
        return $this->runQuery(
            "SELECT CODIGO FROM codigos_mensageria ORDER BY ID DESC LIMIT 1"
        )->getColumn();
    }
}
