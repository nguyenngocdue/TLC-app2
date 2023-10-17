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

    public static function arrayToJsonWithSingleQuotes($item, $sign1='[', $sign2=']')
    {
        if(!is_array($item)) return is_numeric($item) ?  $item : "'$item'";
        return $sign1 . implode(', ', array_map(function ($item) {
            if (is_object($item)){
                if(is_numeric($item->value)) return "$item->value";
                return "'$item->value'";
            } else {
                if(is_numeric($item)) return "$item";
                return "'$item'"; 
            }
        }, $item)) . $sign2;
    }
}
