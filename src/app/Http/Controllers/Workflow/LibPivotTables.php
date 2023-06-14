<?php

namespace App\Http\Controllers\Workflow;

use Illuminate\Support\Str;

class LibPivotTables extends AbstractLib
{
    protected static $key = "pivot-tables";

    public static function getFor($key)
    {
        $all = static::getAll();
        $result = [];
        foreach ($all as $item) {
            if ($item['name'] === $key) {
                $result['name'] = $key;
                foreach ($item as $key1 => $str) {
                    if ($key1 === 'name') continue;
                    $result[$key1] = Str::parseArray($str);
                }
                return $result;
            }
        }
        return [];
    }
}
