<?php

namespace App\Utils\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DBTable
{
    private static $singletonColumns = [];

    public static function fromNameToModel($tableName): Model
    {
        return App::make(Str::modelPathFrom($tableName));
    }

    public static function getAll()
    {
        return DB::select("SHOW tables;");
    }

    public static function getAllColumns($tableName, $keepSingularIfNeeded = false)
    {
        if (!isset(static::$singletonColumns[$tableName]) /*|| !static::$singletonColumns[$tableName]*/) {
            static::$singletonColumns[$tableName] = static::getColumnsExpensive($tableName, $keepSingularIfNeeded);
        }
        return static::$singletonColumns[$tableName];
    }

    private static function getColumnsExpensive($tableName)
    {
        $temp =  DB::select("SHOW COLUMNS FROM $tableName");
        $result = [];
        foreach ($temp as $column) {
            $result[$column->Field] = (array) $column;
        }
        return $result;
    }

    public static function getColumnNames($tableName)
    {
        $table = static::getAllColumns($tableName);
        return array_map(fn ($c) => $c['Field'], $table);
    }

    public static function getColumnTypes($tableName)
    {
        $table = static::getAllColumns($tableName);
        return array_map(fn ($c) => $c['Type'], $table);
    }
}
