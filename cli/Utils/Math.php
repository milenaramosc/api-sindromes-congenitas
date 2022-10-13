<?php

namespace CLI\Utils;

class Math
{
    public static function decTohex(string $number): string
    {
        $hexval = '';
        $hexvalues = [
            '0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'
        ];

        while ($number !== '0') {
            $hexval = $hexvalues[bcmod($number, '16')] . $hexval;
            $number = bcdiv($number, '16', 0);
        }

        return (string) $hexval;
    }
}
