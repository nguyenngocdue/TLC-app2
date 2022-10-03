<?php

namespace App\GetColumnTable;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GetColumnTable
{
    public static function getColumnTable($tableName)
    {
        $data = DB::select("Select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA = '" . env('DB_DATABASE', "'laravel'") . "' and TABLE_NAME = '" . $tableName . "' ORDER BY ORDINAL_POSITION ASC");
        $result = [];
        foreach ($data as $value) {
            $var = array_values((array)$value);
            array_push($result, ...$var);
        }
        return $result;
    }
}
