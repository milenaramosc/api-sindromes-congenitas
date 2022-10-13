<?php

namespace App\Core\Utils;

use App\Core\Handlers\Exceptions\ExceptionHandler;

class ConversorBoleto
{
    public static function toBarCode(string $linha): string
    {
        $barra    = preg_replace('/[^0-9]/', '', $linha);
        $lenBarra = strlen($barra);

        if ($lenBarra < 47) {
            $barra = $barra . substr('00000000000', 0, 47 - $lenBarra);
        }

        if ($lenBarra != 47) {
            return false;
        }

        $barra = substr($barra, 0, 4)
            . substr($barra, 32, 15)
            . substr($barra, 4, 5)
            . substr($barra, 10, 10)
            . substr($barra, 21, 10);

        if (self::modulo11(substr($barra, 0, 4) . substr($barra, 5, 39)) != substr($barra, 4, 1)) {
            return false;
        }

        return $barra;
    }

    public static function toDigitable(string $barra): string
    {
        try {
            $linha = preg_replace("/[^0-9]/", '', $barra);
            if (strlen($linha) !== 44) {
                throw new \Exception('Código de barras diferente de 44 caracteres', 1);
            }

            $campo1 = substr($linha, 0, 4) . substr($linha, 19, 1) . substr($linha, 20, 4);
            $campo2 = substr($linha, 24, 5) . substr($linha, 24 + 5, 5);
            $campo3 = substr($linha, 34, 5) . substr($linha, 34 + 5, 5);
            $campo4 = substr($linha, 4, 1);
            $campo5 = substr($linha, 5, 14);

            if (self::modulo11(substr($linha, 0, 4) . substr($linha, 5, 99)) !== $campo4) {
                throw new \Exception('Campo 4 incorreto', 2);
            }

            if ($campo5 == 0) {
                $campo5 = '000';
            }

            $linha =  $campo1
                . self::modulo10($campo1)
                . $campo2
                . self::modulo10($campo2)
                . $campo3
                . self::modulo10($campo3)
                . $campo4
                . $campo5;

            return $linha;
        } catch (\Throwable $th) {
            (new ExceptionHandler($th, 'E2D3-094', 500))->print();
        }
    }

    private static function modulo10($numero)
    {
        $numero   = preg_replace('/[^0-9]/', '', $numero);
        $soma     = 0;
        $peso     = 2;
        $contador = strlen($numero) - 1;

        while ($contador >= 0) {
            $multiplicacao = (substr($numero, $contador, 1) * $peso);
            if ($multiplicacao >= 10) {
                $multiplicacao = 1 + ($multiplicacao - 10);
            }
            $soma += $multiplicacao;

            if ($peso == 2) {
                $peso = 1;
            } else {
                $peso = 2;
            }
            $contador -= 1;
        }
        $digito = 10 - ($soma % 10);
        if ($digito === 10) {
            $digito = 0;
        }

        return (string) $digito;
    }

    private static function modulo11($numero)
    {
        $numero = preg_replace('/[^0-9]/', '', $numero);
        $soma   = 0;
        $peso   = 2;
        $base   = 9;
        $contador = strlen($numero) - 1;
        for ($i = $contador; $i >= 0; $i--) {
            $soma += (intval(substr($numero, $i, 1)) * $peso);
            if ($peso < $base) {
                $peso++;
            } else {
                $peso = 2;
            }
        }
        $digito = 11 - ($soma % 11);
        if ($digito >  9) {
            $digito = 0;
        }
        /* Utilizar o dígito 1(um) sempre que o resultado do cálculo padrão for igual a 0(zero), 1(um) ou 10(dez). */
        if ($digito === 0) {
            $digito = 1;
        }
        return (string) $digito;
    }
}
