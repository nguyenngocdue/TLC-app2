<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Str;

class LibPivotTables2 extends AbstractLib
{
    protected static $key = "pivot-tables";

    private static function parseArrayWithJson($str, $delimiter = ",")
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
                $result[$string] = (object)["key" => $string];
            }
        }
        // dump($result);
        return $result;
    }

    public static function getFor($key)
    {
        $all = static::getAll();
        $result = [];
        foreach ($all as $item) {
            if ($item['name'] === $key) {
                $result['name'] = $key;
                foreach ($item as $key1 => $str) {
                    if ($key1 === 'name') continue;
                    // dump($key1);
                    $result[$key1] = static::parseArrayWithJson($str, ";");
                    // $result[$key1] = Str::parseArray($str);
                }
                // dump($result);
                return $result;
            }
        }
        return [];
    }
}
