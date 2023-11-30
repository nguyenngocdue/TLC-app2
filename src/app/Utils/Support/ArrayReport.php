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
    public static function calculatePercentBetween2Months($array1, $array2) {
        $result = [];
        foreach ($array2 as $key => $value2) {
            if (array_key_exists($key, $array1)) {
                $number = NumberReport::calculatePercentNumber($array1[$key], $value2);
                $result[$key] = NumberReport::formatNumber($number);
            } else {
                $result[$key] =  null;
            }
        }
        // dump($result);
        return $result;
    }
    public static function separateByYear($data) {
        $result = [];
    
        foreach ($data as $item) {
            foreach ($item as $year => $values) {
                if (!isset($result[$year])) $result[$year] = [];
                $result[$year][] = $values;
            }
        }
        return $result;
    }
    public static function rearrangeArray($data) {
        $result = [];
        foreach ($data as $year => $quarters) {
            foreach ($quarters as $quarter => $value) {
                if (!isset($result[$quarter]))$result[$quarter] = [];
                $result[$quarter][$year] = $value;
            }
        }
        return $result;
    }
    

}
