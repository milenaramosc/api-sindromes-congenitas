<?php

namespace App\Core\Utils;

class Money
{
    /**
     * @name floatToDB Formata um float para "1000.00"
     * @exemple (1000, 2) retorna "1000.00" | 1000.0000 retorna "1000.00"
     * @param float $value
     * @param int $decimals Quantidade de casas decimais
     * @return string
     */
    public static function floatToDB($value, $decimals = 2)
    {
        return number_format($value, $decimals, '.', '');
    }

    /**
     * @name floatToBrMoney Formata um float para "1.000,00"
     * @exemple (1000, 2) retorna "1.000,00" | 1000.0000 retorna "1.000,00"
     * @param float $value
     * @param int $decimals Quantidade de casas decimais
     * @return string
     */
    public static function floatToBrMoney($value, $decimals = 2)
    {
        return number_format($value, $decimals, ',', '.');
    }

    public static function showBrl($value): string
    {
        return "R$ " . number_format($value, 2, ',', '.');
    }

    /**
     * @name moedaSemMascara remove as máscaras de um valor monetário
     * @exemple (float) 99.99 retorna string "9999"
     * @param float $valor
     * @return string
     */
    public static function moedaSemMascara($valor = 0)
    {
        $valor = Money::floatToDB($valor);
        return str_replace('.', '', $valor);
    }

    public static function moedaBD($string)
    {
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        return floatval($string);
    }

    public static function moedaBDTWO($string): float
    {
        $string = (strpos($string, ".") > -1 && strpos($string, ",") === false)
            ? str_replace(".", ",", $string)
            : $string;

        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);

        return floatval($string);
    }

    public static function formatMoeda($string)
    {
        $string = str_replace('R$', '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace(',', '.', $string);
        $string = str_replace('.', '.', $string);
        return $string;
    }

    /**
     * @name valorInteiroParaFloat
     * @exemple Recebe os valores no formato "1000" e retorna 10.00
     * @param string|int $valor
     * @return float
     */
    public static function valorInteiroParaFloat($valor = 0)
    {
        return (float) ((int) $valor / 100);
    }
}
