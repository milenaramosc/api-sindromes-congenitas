<?php

namespace App\Core\Utils;

class Graficos
{
    public static function buscaSemanal()
    {

        $data = array("inicial" => '', "final" => '');
        $numeroDiaSemanal = date('w', time());

        $data['inicial'] = date('Y-m-d', strtotime("- $numeroDiaSemanal day"));
        $numeroDiaSemanalFinal = 6 - $numeroDiaSemanal;
        $data['final'] = date('Y-m-d', strtotime("+ $numeroDiaSemanalFinal day"));

        $d1 = $data['inicial'];
        $d2 = $data['final'];

        $timestamp1 = strtotime($d1);
        $timestamp2 = strtotime($d2);

        $array = array();


        //$diaSemana = array("Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado");
        $diaSemana = array("0", "1", "2", "3", "4", "5", "6");

        for ($cont = 0; $timestamp1 <= $timestamp2; $cont++) {
            $array[$diaSemana[$cont]] = date('Y-m-d', $timestamp1);
            $timestamp1 += 86400;
        }

        return $array;
    }


    public static function buscaMensal()
    {

        $mes = date('m');
        $ano  = date('Y');

        $numeroDeDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

        $dias = array();

        for ($dia = 1; $dia <= $numeroDeDias; $dia++) {
            $dias[] = str_pad($dia, 2, '0', STR_PAD_LEFT) . '/' . $mes . '/' . $ano;
        }

        return $dias;
    }
}
