<?php

namespace App\Core\Helpers;

use DateTime;
use DateTimeZone;

class DateFormat
{
    /**
     * Formata uma data com o formato UTC
     * Com milisegundos Y-m-d\TH:i:s.v\Z
     * Sem milisegundos Y-m-d\TH:i:s\Z
     *
     * @param DateTime|null $dateTime Data para formatar
     *
     * @return string Data formatada ou string vazia
     */
    public static function utc(?DateTime $dateTime, bool $withMilliseconds = true): string
    {
        if ($dateTime === null) {
            return "";
        }

        $formats = [
            true => "Y-m-d\TH:i:s.v\Z",
            false => "Y-m-d\TH:i:s\Z"
        ];

        $dateTime->setTimezone(new DateTimeZone("UTC"));
        $date = $dateTime->format($formats[$withMilliseconds]);
        $dateTime->setTimezone(new DateTimeZone(DEFAULT_TIME_ZONE));
        return $date;
    }
}
