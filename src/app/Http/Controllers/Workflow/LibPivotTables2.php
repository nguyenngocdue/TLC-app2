<?php

namespace App\Http\Controllers\Workflow;

class LibPivotTables2 extends AbstractLib
{
    protected static $key = "pivot-tables";

    private static function parseArrayWithJson($str, $delimiter = ",", $modeKey)
    {
        $str = str_replace("\r\n", "", $str);
        $strings = explode($delimiter, $str);
        $strings = array_filter($strings, fn ($s) => $s);
        // dump($strings);
        $result = [];
        foreach ($strings as $string) {
            if (str_contains($string, "{")) {
                [$key, $json] = explode("{", $string);
                $result[$key] = json_decode("{" . $json);
            } else {
                // dd($str, $strings, $string);
                $result[$modeKey] = (object)["is_dataSource" => (bool)$string];
            }
        }
        // dump($result);
        return $result;
    }

    public static function getFor($modeKey)
    {
        $all = static::getAll();
        $result = [];
        foreach ($all as $item) {
            if ($item['name'] === $modeKey) {
                $result['name'] = $modeKey;
                foreach ($item as $key1 => $str) {
                    if ($key1 === 'name') continue;
                    $result[$key1] = static::parseArrayWithJson($str, ";", $modeKey);
                }
                // dump($result);
                return $result;
            }
        }
        return [];
    }
}
