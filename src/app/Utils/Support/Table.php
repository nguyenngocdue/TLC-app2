<?php

namespace App\Utils\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Table
{
    private static $singleton = [];

    public static function getColumn($tableName)
    {
        if (!isset(static::$singleton[$tableName]) || !static::$singleton[$tableName]) {
            static::$singleton[$tableName] = static::getColumnExpensive($tableName);
        }
        return static::$singleton[$tableName];
    }
    private static function getColumnExpensive($tableName)
    {
        $data = DB::select("Select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA = '" . env('DB_DATABASE', "laravel") . "' and TABLE_NAME = '" . $tableName . "' ORDER BY ORDINAL_POSITION ASC");
        $result = [];
        foreach ($data as $value) {
            $var = array_values((array)$value);
            array_push($result, ...$var);
        }
        return $result;
    }
}
