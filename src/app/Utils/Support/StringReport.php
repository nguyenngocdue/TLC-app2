<?php

namespace App\Utils\Support;


class StringReport
{
    public static function parseKeyValueString($inputString)
    {
        $result = [];
        if (is_null($inputString) || is_array($inputString)) return [];
        foreach (explode(",", $inputString) as $pair) {
            list($key, $value) = explode(":", $pair);
            $result[$key] = $value;
        }
        return $result;
    }

    public static function arrayToJsonWithSingleQuotes($array)
    {
        return '[' . implode(', ', array_map(function ($item) {
            if(is_numeric($item)) return "$item";
            return "'$item'"; 
        }, $array)) . ']';
    }
}
