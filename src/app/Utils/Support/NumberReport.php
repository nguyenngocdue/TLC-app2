<?php

namespace App\Utils\Support;

class NumberReport
{
    public static function formatNumber($number, $decimal=2){
        $number = (float)str_replace(',', '', (string)$number);
        return number_format($number, $decimal);
    }

    public static function calculatePercentNumber($value1, $value2){
        $number = $value2 && $value1 > 0 ? round((((float)$value2 - (float)$value1)*100 / (float)$value1), 2) : 0;
        $number = $number > 0  ? 100 - $number : -1*($number !== 0 ? 100 - abs($number) : null);
        return $number;
    }
}
