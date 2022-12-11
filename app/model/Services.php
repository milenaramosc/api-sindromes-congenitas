<?php

namespace App\Model;

use App\Core\Handlers\Exceptions\ExceptionHandler;
use App\Core\Helpers\ModelHelper;

class Services extends ModelHelper
{
    public function __construct()
    {
    }

    public function getById(int $id): array
    {
        try {
            return $this->runQuery(
                "SELECT 
                    NOME_SERVICO, PARCEIRO, CLASS, URL, AUTH_URL, 
                    LOGIN, PASSWORD, TOKEN, EXPIRES_IN 
                FROM services 
                    WHERE ID = :ID 
                    AND ATIVO = 1",
                [":ID" => $id]
            )->getArray();
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function updateToken(int $id, string $token, string $expiresIn)
    {
        try {
            $this->runQuery(
                "UPDATE services SET TOKEN = :TOKEN AND EXPIRES_IN = :EXPIRES_IN WHERE ID = :ID",
                [
                    ":TOKEN"      => $token,
                    ":EXPIRES_IN" => $expiresIn,
                    ":ID"         => $id,
                ]
            );
        } catch (\Throwable $th) {
            (new ExceptionHandler($th, 'E4B1-001'))->print();
        }
    }

    public function getNextId(): int
    {
        return (int) $this->runQuery(
            "SELECT ID FROM services ORDER BY ID DESC LIMIT 1"
        )->getColumn() + 1;
    }
}
