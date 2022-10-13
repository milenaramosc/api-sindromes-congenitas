<?php

namespace App\Core\Helpers;

class ArrayHelper
{
    /**
     * @param array $mergeAt Array que receberá os dados de um segundo array
     * @param array $merge Array que será mesclado
     * @param string|int $columnName
     * @return array
     */
    public static function mergeByArrayColumnValue(array $mergeAt, array $merge, $columnName): array
    {
        foreach ($mergeAt as $key => $value1) {
            foreach ($merge as $value2) {
                if ($value1[$columnName] === $value2[$columnName]) {
                    $mergeAt[$key] = $value2;
                }
            }
        }

        return $mergeAt;
    }

    /**
     * Ajusta um array para que se torne key => value
     *
     * @param array  $array
     * @param string $keyColumn
     * @param string $valueColumn
     *
     * @return array
     */
    public static function keyValueArrayFromColumns(array $array, string $keyColumn, string $valueColumn): array
    {
        $aux = [];
        foreach ($array as $value) {
            $aux[$value[$keyColumn]] = $value[$valueColumn];
        }

        return $aux;
    }
}
