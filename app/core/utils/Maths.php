<?php

namespace App\Core\Utils;

class Maths
{
    public static function roundDown($number, $precision = 2)
    {
        $fig = (int) str_pad('1', $precision, '0');
        return (floor($number * $fig) / $fig);
    }

    public static function calculaParcelaPrecisa($name, $arrayParcelas, $total)
    {
        $somaParcelas = array_sum(array_column($arrayParcelas, $name));
        $diff = $somaParcelas - $total;
        if ($diff != 0) {
            if ($diff < 0) {
                $diff *= -1;
            }
            $arrayParcelas[0][$name] += $diff;
        }
        return $arrayParcelas;
    }

    public static function calculaTaxasPrecisaParcelas($name, $arrayNames, $arrayParcelas, $total)
    {
        //$arrayName - nomes das parcelas a somar, total tem q ser o mesmo dda variavel total
        $totalTaxaFinanciamento = array_sum(array_column($arrayParcelas, $arrayNames[0]));
        $totalTaxaProcessamento = array_sum(array_column($arrayParcelas, $arrayNames[1]));
        $diff = $total - ($totalTaxaFinanciamento + $totalTaxaProcessamento);

        if ($diff != 0) {
            if ($diff < 0) {
                $diff *= -1;
            }
            $arrayParcelas[0][$name] = round($arrayParcelas[0][$name] += $diff, 2);
        }

        return $arrayParcelas;
    }

    public static function calcularDiasAntecipacao($data, $parcelas)
    {
        /* NOVO CALCULO DE TAXAS POR DIFERENÇA DE DIAS  */
        $diferenca = 0;
        for ($i = 1; $i <= $parcelas; $i++) {
            $dateUltimaParcela = date('Y-m-d H:m:s', strtotime($data . ' +' . ($i * 30) . ' day'));
            // Calcula a diferença em segundos entre as datas
            $diferenca += strtotime($dateUltimaParcela) - strtotime($data);
            //Calcula a diferença em dias
        }
        //var_dump($parcelas,floor($diferenca / (60 * 60 * 24)));
        return floor($diferenca / (60 * 60 * 24));
    }
}
