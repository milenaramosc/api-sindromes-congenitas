<?php

namespace App\Core\Utils;

use App\Services\FeriadosNacionais\FeriadosNacionais;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;

class Data
{
    public const DATE_TIME_FORMAT = "Y-m-d H:i:s";
    public const DATE_FORMAT = "Y-m-d";
    public const TIME_FORMAT = "H:i:s";

    public static function gravaData($data)
    {
        if (strlen($data) == 10) {
            $vtdt = explode('/', $data);
            $datafinal = $vtdt[2] . '-' . $vtdt[1] . '-' . $vtdt[0];
            return $datafinal;
        } else {
            return "";
        }
    }

    public static function mostraData($data)
    {
        if (strlen($data) >= 10) {
            $data2 = explode(' ', $data);
            $vtdt = explode('-', $data2[0]);
            $datafinal = $vtdt[2] . '/' . $vtdt[1] . '/' . $vtdt[0];
            return $datafinal;
        } else {
            return "";
        }
    }

    public function date2dataFull($data)
    {
        $vtdt1 = explode(' ', $data);
        $data2 = $vtdt1[0];
        $hora = $vtdt1[1];
        if (strlen($data2) == 10) {
            $vtdt = explode('-', $data2);
            $datafinal = $vtdt[2] . '/' . $vtdt[1] . '/' . $vtdt[0];
            return $datafinal . " às " . $hora;
        } else {
            return "";
        }
    }

    public static function date2dataRBM($data)
    {
        $vtdt1 = explode(' ', $data);
        $data2 = $vtdt1[0];
        $hora = $vtdt1[1];
        if (strlen($data2) == 10) {
            $vtdt = explode('-', $data2);
            $datafinal = $vtdt[2] . '/' . $vtdt[1];

            $vthr = explode(':', $hora);
            return $datafinal . " " . $vthr[0] . ":" . $vthr[1];
        } else {
            return "";
        }
    }

    public static function date2data($data)
    {
        if (strlen($data) >= 10) {
            $data2 = explode(' ', $data);
            $vtdt = explode('-', $data2[0]);
            $datafinal = $vtdt[2] . '/' . $vtdt[1] . '/' . $vtdt[0];
            return $datafinal;
        } else {
            return "";
        }
    }
    public static function data2date($data)
    {
        if (strlen($data) == 10) {
            $vtdt = explode('/', $data);
            $datafinal = $vtdt[2] . '-' . $vtdt[1] . '-' . $vtdt[0];
            return $datafinal;
        } else {
            return "";
        }
    }
    public static function dataSimples($data)
    {
        $vmes = array("", "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");
        $vtdt1 = explode(' ', $data);

        //        echo '<pre>';print_r($vtdt1);exit;

        $data2 = $vtdt1[0];
        $hora = $vtdt1[1];
        if (strlen($data2) == 10) {
            $vtdt = explode('-', $data2);
            $datafinal = $vtdt[2] . ' ' . $vmes[(int)$vtdt[1]];
            return $datafinal;
        } else {
            return "";
        }
    }

    public static function pegarDataSemana()
    {

        $data = array("inicial" => '', "final" => '');

        $numeroDiaSemanal = date('w', time());
        $data['inicial'] = date('Y-m-d', strtotime("- $numeroDiaSemanal day"));
        $numeroDiaSemanalFinal = 6 - $numeroDiaSemanal;
        $data['final'] = date('Y-m-d', strtotime("+ $numeroDiaSemanalFinal day"));

        return $data;
    }

    public static function formatandoDataPraEmail($data)
    {

        $mesDesc = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

        $manipulador = explode(' ', $data);

        $data = $manipulador[0];
        $hora = $manipulador[1];


        $manipulador = explode('-', $data);


        $dia = $manipulador[2];
        $mes = $manipulador[1];
        $ano = $manipulador[0];

        return $dia . ' de ' . $mesDesc[$mes] . ' ' . $ano . ' ás ' . $hora;
    }

    public static function inverteAno($datavelha)
    {

        $quebra = explode(" ", $datavelha);

        $quebradata = explode("-", $quebra[0]);

        $datanova = $quebradata[2] . '-' . $quebradata[1] . '-' . $quebradata[0] . ' ' . $quebra[1];

        return $datanova;
    }


    public static function pegarDataMes($formatoData = 'Y-m-d')
    {

        $data_atual = date($formatoData);

        return $data_atual;
    }

    // SOMA DIAS À DATA ESPECIFICADA.
    public static function somarDias($data, $dias, $formatoData = 'Y-m-d')
    {

        if ($formatoData == 'Y-m-d') {
            $data_auxiliar = explode("-", $data);
            $data = $data_auxiliar[2] . "/" . $data_auxiliar[1] . "/" . $data_auxiliar[0];
        }

        $data_array = explode("/", $data);
        $nova_data = date($formatoData, mktime(0, 0, 0, $data_array[1], $data_array[0] + $dias, $data_array[2]));

        return $nova_data;
    }

    // SOMA MESES À DATA ESPECIFICADA.
    public static function somarMeses($data, $meses, $formatoData = 'Y-m-d')
    {

        if ($formatoData == 'Y-m-d') {
            $data_auxiliar = explode("-", $data);
            $data = $data_auxiliar[2] . "/" . $data_auxiliar[1] . "/" . $data_auxiliar[0];
        }

        $data_array = explode("/", $data);
        $nova_data = date($formatoData, mktime(0, 0, 0, $data_array[1] + $meses, $data_array[0], $data_array[2]));

        return $nova_data;
    }

    // SOMA ANOS À DATA ESPECIFICADA.
    public static function somarAnos($data, $anos, $formatoData = 'Y-m-d')
    {

        if ($formatoData == 'Y-m-d') {
            $data_auxiliar = explode("-", $data);
            $data = $data_auxiliar[2] . "/" . $data_auxiliar[1] . "/" . $data_auxiliar[0];
        }

        $data_array = explode("/", $data);
        $nova_data = date($formatoData, mktime(0, 0, 0, $data_array[1], $data_array[0], $data_array[2]  + $anos));

        return $nova_data;
    }

    // DIFERENÇA DE DIAS ENTRE DATAS
    public static function diferencaDiasEntreDatas($primeira_data, $segunda_data, $formato_data = 'Y-m-d')
    {

        if ($formato_data == 'Y-m-d') {
            $primeira_data_auxiliar = explode("-", $primeira_data);
            $primeira_data = $primeira_data_auxiliar[2] . "/" . $primeira_data_auxiliar[1] . "/" . $primeira_data_auxiliar[0];

            $segunda_data_auxiliar = explode("-", $segunda_data);
            $segunda_data = $segunda_data_auxiliar[2] . "/" . $segunda_data_auxiliar[1] . "/" . $segunda_data_auxiliar[0];
        }

        $primeira_data_array = explode("/", $primeira_data);
        $primeira_data_inteira = mktime(0, 0, 0, $primeira_data_array[1], $primeira_data_array[0], $primeira_data_array[2]);

        $segunda_data_array = explode("/", $segunda_data);
        $segunda_data_inteira = mktime(0, 0, 0, $segunda_data_array[1], $segunda_data_array[0], $segunda_data_array[2]);

        $diferenca_em_dias = $primeira_data_inteira > $segunda_data_inteira
            ? ($primeira_data_inteira - $segunda_data_inteira)
            : ($segunda_data_inteira - $primeira_data_inteira);

        return Intval(round($diferenca_em_dias / 86400));
    }

    /**
     * Retorna a diferença em segundos de uma data para Greenwich
     * @param string $date
     * @return int
     */
    public static function getDateTimeZoneGMTOffset($date)
    {
        $dateTime = new DateTime($date);
        return (int) $dateTime->format('Z');
    }

    /**
     * Retorna a diferença em segundos do fuso horário padrão do sistema para Greenwich
     * @return int
     */
    public static function getDefaultTimeZoneGMTOffset()
    {
        $dateTimeZone = new DateTimeZone(DEFAULT_TIME_ZONE);
        return (int) $dateTimeZone->getOffset(new DateTime());
    }

    /**
     * Converte uma data em um determinado fuso horário para o padrão do sistema
     * @param string $date Data que possua o fuso horário, por exemplo "2021-10-26T11:17:42.000-04:00"
     * @param string $format
     * @return string
     */
    public static function toDefaultTimeZone($date, $format = self::DATE_TIME_FORMAT)
    {

        $dateOffset      = self::getDateTimeZoneGMTOffset($date);
        $timeZoneName    = TimeZone::getTimeZoneNamesByOffset($dateOffset);
        $timeZone        = new DateTimeZone($timeZoneName[0]);
        $datetime        = new DateTime($date, $timeZone);
        $defaultTimeZone = new DateTimeZone(DEFAULT_TIME_ZONE);
        $datetime->setTimezone($defaultTimeZone);
        return $datetime->format($format);
    }

    public static function somarMinutos($date, $add)
    {
        $time = new DateTime($date);
        $time->add(new DateInterval('PT' . $add . 'M'));
        return $time->format('Y-m-d H:i:s');
    }

    public static function formatDateBr($date)
    {
        try {
            if (!empty($date) && strpos($date, '-')) {
                $dateTime = new DateTime($date);
                return $dateTime->format('d/m/Y');
            }
            return $date;
        } catch (\Throwable $th) {
            return $date;
        }
    }

    /**
     * Retorna TRUE se uma data informada não for válida
     *
     * @param string|null $date
     * @return boolean
     */
    public static function dateIsNotValid(?string $date): bool
    {
        if (empty($date)) {
            return true;
        }
        if ($date === '0000-00-00') {
            return true;
        }

        $date = new DateTimeImmutable($date);
        if ((int) $date->format('Y') <= 0) {
            return true;
        }

        return false;
    }

    /**
     * Retorna um array com as datas do range, incluindo a inicial e a final
     *
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     * @param int               $days
     *
     * @return array
     */
    public static function rangeDatas(DateTimeImmutable $startDate, DateTimeImmutable $endDate): array
    {
        $start = $startDate->getTimestamp();
        $end   = $endDate->getTimestamp();

        $dates = [];
        for ($i = $start; $i <= $end; $i += 86400) {
            $dates[] = date("Y-m-d", $i);
        }

        return $dates;
    }

    /**
     * Converte uma string de data/hora para um objeto DateTime ou null
     *
     * @return DateTime|null
     */
    public static function toDateTimeOrNull(?string $dateTime): ?DateTime
    {
        if ($dateTime === null) {
            return null;
        }

        return new DateTime($dateTime);
    }

    /**
     * Verifica se o dia informado é útil
     *
     * @param string $date Formato Y-m-d
     *
     * @return bool
     */
    public static function isWorkingDay(string $date): bool
    {
        if (self::isWeekend($date)) {
            return false;
        }

        $holidays = self::getFeriadosNacionais($date, $date);
        return !in_array($date, $holidays);
    }

    public static function isNotWorkingDay(string $date): bool
    {
        return !self::isWorkingDay($date);
    }

    /**
     * Verifica se a data informada é Sábado ou Domingo
     *
     * @param string $date Formato Y-m-d
     *
     * @return bool
     */
    public static function isWeekend(string $date)
    {
        return in_array(
            date('w', strtotime($date)),
            [0, 6]
        );
    }

    /**
     * Consulta os feriados em um determinado período
     *
     * @param string $initialDate
     * @param string $endDate
     *
     * @return array
     */
    public static function getFeriadosNacionais($initialDate, $endDate): array
    {
        $feriadosNacionais = new FeriadosNacionais();
        return $feriadosNacionais->consultar(
            new DateTimeImmutable($initialDate),
            new DateTimeImmutable($endDate)
        );
    }
}
