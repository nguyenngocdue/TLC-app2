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
        $tables = [];
        $tableNames =  DB::select("SHOW tables;");
        foreach ($tableNames as $tableNameObj) $tables[] = $tableNameObj->Tables_in_laravel;
        return $tables;
    }

    public static function getAllColumns($tableName, $keepSingularIfNeeded = false)
    {
        if (!isset(static::$singletonColumns[$tableName]) /*|| !static::$singletonColumns[$tableName]*/) {
            static::$singletonColumns[$tableName] = static::getColumnsExpensive($tableName, $keepSingularIfNeeded);
        }
        return static::$singletonColumns[$tableName];
    }

    private static $relationships = null;
    public static function getRelationships($tableName)
    {
        if (is_null(static::$relationships)) {
            $temp = DB::select("SELECT 
            TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE 1=1
                AND REFERENCED_TABLE_SCHEMA = 'laravel' 
                ;");
            foreach ($temp as $relation) {
                static::$relationships[$relation->TABLE_NAME][] = (array)$relation;
                // dump($relation->TABLE_NAME);
            }
        }

        return static::$relationships[$tableName] ?? [];
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
