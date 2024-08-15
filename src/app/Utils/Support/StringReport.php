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

    public static function arrayToJsonWithSingleQuotes($item, $sign1 = '[', $sign2 = ']')
    {
        if (!is_array($item)) return is_numeric($item) ?  $item : "'$item'";
        return $sign1 . implode(', ', array_map(function ($item) {
            if (is_object($item)) {
                $val = ucfirst($item->value);
                if (is_numeric($val)) return "$val";
                return "'$val'";
            } else {
                $item = ucfirst($item);
                if (is_numeric($item)) return "$item";
                return "'$item'";
            }
        }, $item)) . $sign2;
    }

    public static function arrayToJsonWithSingleQuotes2($item, $apostrophe = false)
    {
        if (!is_array($item)) return is_numeric($item) ?  $item : "'$item'";
        return  implode(', ', array_map(function ($item) use ($apostrophe) {
            if (is_object($item)) {
                $val =  $item->value;
                if (is_numeric($val)) return "$val";
                return "'$val'";
            } else {
                if (is_numeric($item)) {
                    if ($apostrophe) {
                        return "'$item'";
                    } else {
                        return "$item";
                    }
                };
                return "'$item'";
            }
        }, $item));
    }

    public static function separateStringsByDot($data)
    {
        array_walk($data, function (&$value, $key) {
            if (str_contains($value, '.')) {
                $items = explode('.', $value);
                $array = [];
                foreach ($items as $k => $val) {
                    $array['param_' . $k] = $val;
                }
                $value = $array;
            }
        });
        return $data;
    }

    public static function stringsPad($array)
    {
        return array_map(fn ($item) => str_pad($item, 2, '0', STR_PAD_LEFT), $array);
    }

    public static function removeNumbersAndChars($inputString)
    {
        $resultString = preg_replace("/[^a-zA-Z\s]/", '', $inputString);
        return $resultString;
    }

    public static function fixDecimal($value)
    {
        return str_replace(',', '', number_format($value, 2));
    }

    public static function createUrlParam($objects, $key = null, $value = null)
    {
        if (is_array($objects) && !empty($objects)) {
            return http_build_query($objects, '', '&', PHP_QUERY_RFC3986);
        }
        return $key . '=' . $value;
    }

    public static function makeTitleFilter($string){
        return  ucwords(str_replace('_', ' ', $string));
    }
}
