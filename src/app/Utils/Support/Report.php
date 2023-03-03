<?php

namespace App\Utils\Support;


class Report
{
    public static function groupArrayByKey($dataSource, $key)
    {
        $groupedArray = [];
        foreach ($dataSource as $element) {
            $eleArray = (array)$element;
            $groupedArray[$eleArray[$key]][] = $eleArray;
        }
        // dd($groupedArray, $dataSource);
        return $groupedArray;
    }
    public static function mergeArrayValues($grouped_array)
    {
        $result = [];
        foreach (array_keys($grouped_array) as $key) {
            $result[] = array_merge(...$grouped_array[$key]);
        }
        return $result;
    }
    public static function slugName($string)
    {
        $strLower = strtolower($string);
        return preg_replace('/[[:space:]]+/', "_", $strLower);
    }
    public static function pressArrayTypeAllItems($dataSource)
    {
        $array = [];
        foreach ($dataSource as $key => $value) {
            $array[] = (array)$value;
        }
        return $array;
    }
}
