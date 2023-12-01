<?php

namespace App\Utils\Support;

class NumberReport
{
    public static function formatNumber($number, $decimal=2){
        $number = (float)str_replace(',', '', (string)$number);
        return number_format($number, $decimal);
    }

    public static function calculatePercentNumber($value1, $value2){
        $value2 = (float)$value2;
        $value1 = (float)$value1;
        $number = (float)($value2 - $value1) !== (float)0 && $value2 && $value1 > 0 ? round((($value2 - $value1)*100 / $value1), 2) : 0;
        $number = $number > 0  ? $number : -1*($number !== 0 ? abs($number) : null);
        return $number;
    }

    public static function sumByMonth($data) {
        $result = [];
        foreach ($data as $item) {
            foreach ($item as $month => $value) {
                if (!isset($result[$month])) $result[$month] = 0;
                $result[$month] += $value;
            }
        }
        return $result;
    }
}
