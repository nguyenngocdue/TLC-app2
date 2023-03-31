<?php

namespace App\Utils\Support;


class Report
{
    public static function getFirstItemFromChildrenArray($dataSource)
    {
        foreach ($dataSource as $key => $values) {
            $dataSource[$key] = (array)reset($values);
        }
        return $dataSource;
    }

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

    public static function groupArrayByKey2($dataSource, $key, $returnKey, $returnValue)
    {
        $groupedArray = [];
        foreach ($dataSource as $element) {
            $eleArray = (array)$element;
            $groupedArray[$eleArray[$key]][$eleArray[$returnKey]] = $eleArray[$returnValue];
        }
        // dd($groupedArray, $dataSource);
        return $groupedArray;
    }

    public static function assignKeyByKey($dataSource, $keyName)
    {
        $array = [];
        foreach ($dataSource as $element) {
            $element = is_object($element) ? (array)$element : $element;
            $array[$element[$keyName]] = $element;
        }
        return $array;
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
    public static function makeTitle($string)
    {
        return ucwords(str_replace('_', ' ', $string));
    }

    public static function pressArrayTypeAllItems($dataSource)
    {
        $array = [];
        foreach ($dataSource as $key => $value) {
            $array[] = (array)$value;
        }
        return $array;
    }
    public static function replaceAndUcwords($string)
    {
        return  ucwords(str_replace('_', " ", $string));
    }
    public static function isNullModeParams($modeParams)
    {
        // dd($modeParams);
        return count(array_filter($modeParams, fn ($value) => !is_null($value))) <= 0;
    }
}
