<?php

namespace Modules\Services;

trait ServiceReturnTrait
{
    /**
     * Retorno de sucesso
     *
     * @param array $data
     * @return array
     */
    public function success(array $data = []): array
    {
        return array_merge(["error" => false], $data);
    }

    /**
     * Retorno de erro
     *
     * @param array $details
     * @return array
     */
    public function error(array $details = []): array
    {
        return ["error" => true, "details" => $details];
    }
}
