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
                $val = ucfirst($item->value);
                if(is_numeric($val)) return "$val";
                return "'$val'";
            } else {
                $item = ucfirst($item);
                if(is_numeric($item)) return "$item";
                return "'$item'"; 
            }
        }, $item)) . $sign2;
    }

    public static function arrayToJsonWithSingleQuotes2($item)
    {
        if(!is_array($item)) return is_numeric($item) ?  $item : "'$item'";
        return  implode(', ', array_map(function ($item){
            if (is_object($item)){
                $val =  $item->value;
                if(is_numeric($val)) return "$val";
                return "'$val'";
            } else {
                if(is_numeric($item)) return "$item";
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
}
