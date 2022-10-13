<?php

namespace Modules\Configuracoes;

use App\Core\Helpers\ModelHelper;

class ConfiguracoesModel extends ModelHelper
{
    public function __construct()
    {
    }

    public function get(int $id): ?string
    {
        try {
            return (string) $this->runQuery(
                "SELECT VALOR FROM configuracoes WHERE ID = :id",
                [":id" => $id]
            )->getColumn();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
