<?php

namespace App\Utils\Support;

class ArrayReport
{
    public static function sumAndMergeItems($items)
    {
        $sums = [];
        foreach ($items as $array) {
            foreach ($array as $month => $value) {
                $sums[$month] = isset($sums[$month]) ? $sums[$month] + $value : $value;
            }
        }
        return $sums;
    }
    public static function subtractArrays($array1, $array2) {
        $result = [];
        foreach ($array2 as $key => $value2) {
            if (array_key_exists($key, $array1)) {
                $result[$key] = (float)$value2 - (float)$array1[$key];
            } else {
                $result[$key] = (float)$value2;
            }
        }
        return $result;
    }
    

}
