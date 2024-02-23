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
    public static function calculatePercentBetween2Months($array1, $array2)
    {
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
    public static function separateByYear($data)
    {
        $result = [];

        foreach ($data as $item) {
            foreach ($item as $year => $values) {
                if (!isset($result[$year])) $result[$year] = [];
                $result[$year][] = $values;
            }
        }
        return $result;
    }
    public static function rearrangeArray($data)
    {
        $result = [];
        foreach ($data as $year => $quarters) {
            foreach ($quarters as $quarter => $value) {
                if (!isset($result[$quarter])) $result[$quarter] = [];
                $result[$quarter][$year] = $value;
            }
        }
        return $result;
    }

    public static function addZeroBeforeNumber($values)
    {
        return array_map(fn ($item) => str_pad($item, 2, '0', STR_PAD_LEFT), $values);
    }

    public static function mergeCommonKeys($data)
    {
        $result = [];
        foreach ($data as $item) {
            foreach ($item as $commonKey => $commonKeyData) {
                if (!isset($result[$commonKey])) {
                    $result[$commonKey] = [];
                }
                foreach ($commonKeyData as $index => $value) {
                    $result[$commonKey][$index][] = $value;
                }
            }
        }
        return $result;
    }

    public static function areSpecificKeysAllNull($array, $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array) && $array[$key] !== null) return false;
        }
        return true;
    }

    public static function checkAllTrue($array)
    {
        $uniqueValues = array_unique($array);
        return count($uniqueValues) === 1 && reset($uniqueValues) === true;
    }

    public static function sliceArrayByKeyRange($data, $startKey, $endKey, $includeKey = true)
    {
        $result = [];
        $startCollecting = false;
        foreach ($data as $key => $value) {
            if ($key === $startKey) {
                $startCollecting = true;
                if (!$includeKey) continue;
            }
            if ($key === $endKey) break;
            if ($startCollecting) $result[$key] = $value;
        }

        return $result;
    }

    public static function sumValuesOfArray($array1, $array2)
    {
        return array_map(function ($a, $b) {
            $a = floatval(str_replace(',', '', $a));
            $b = floatval(str_replace(',', '', $b));
            return number_format($a + $b, 2);
        }, $array1, $array2);
    }

    public static function summaryAllValuesOfArray($data)
    {
        return array_reduce($data, function ($carry, $item) {
            return $carry ? self::sumValuesOfArray($carry, $item) : $item;
        }, []);
    }
}
