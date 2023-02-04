<?php

namespace App\Utils\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DBTable
{
    private static $singletonNames = [];
    private static $singletonTypes = [];


    public static function fromNameToModel($tableName): Model
    {
        return App::make(Str::modelPathFrom($tableName));
    }

    public static function getColumnTypes($tableName)
    {
        if (!isset(static::$singletonTypes[$tableName]) || !static::$singletonTypes[$tableName]) {
            static::$singletonTypes[$tableName] = static::getColumnTypesExpensive($tableName);
        }
        return static::$singletonTypes[$tableName];
    }

    private static function getColumnTypesExpensive($tableName)
    {
        $columnNames = self::getColumnNames($tableName);

        $columnTypes = [];
        foreach ($columnNames as $columnName) {
            $typeColumn = Schema::getColumnType(Str::plural($tableName), $columnName);
            $columnTypes[] = $typeColumn;
        }
        return $columnTypes;
    }

    public static function getColumnNames($tableName)
    {
        if (!isset(static::$singletonNames[$tableName]) || !static::$singletonNames[$tableName]) {
            static::$singletonNames[$tableName] = static::getColumnNamesExpensive($tableName);
        }
        return static::$singletonNames[$tableName];
    }

    private static function getColumnNamesExpensive($tableName)
    {
        $tableName = Str::plural($tableName);
        $data = DB::select("Select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA = '" . env('DB_DATABASE', "laravel") . "' and TABLE_NAME = '" . $tableName . "' ORDER BY ORDINAL_POSITION ASC");
        $result = [];
        foreach ($data as $value) {
            $var = array_values((array)$value);
            array_push($result, ...$var);
        }
        return $result;
    }
}
