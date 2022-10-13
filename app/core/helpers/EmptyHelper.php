<?php

namespace App\Core\Helpers;

class EmptyHelper
{
    /**
     * Conta quantos índices estão vazios em um array
     *
     * @param array $array
     * @return integer
     */
    public static function countEmpty(array $array): int
    {
        $count = 0;
        foreach ($array as $value) {
            if (empty($value)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Retorna quais índices estão vazios em um array
     *
     * @return array
     */
    public static function getEmptyIndexes($array): array
    {
        $aux = [];
        foreach ($array as $key => $value) {
            if (empty($value)) {
                $aux[] = $key;
            }
        }
        return $aux;
    }

    /**
     * Retorna true se algum índice do array está vazio
     *
     * @param array $array
     * @return boolean
     */
    public static function arrayAnyEmpty(array $array): bool
    {
        foreach ($array as $value) {
            if (empty($value)) {
                return true;
            }
        }
        return false;
    }
}
