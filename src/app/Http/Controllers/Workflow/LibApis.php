<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\Entities;

class LibApis extends AbstractLib
{
    protected static $key = "apis";

    public static function getAll()
    {
        $result = [];
        $json = parent::getAll();
        $apps = Entities::getAll();
        foreach ($apps as $app) {
            $tableName = $app->getTable();
            if (isset($json[$tableName])) {
                $line = $json[$tableName];
            } else {
                $line = [
                    'name' => $tableName,
                    'row_color' => 'green',
                ];
            }
            $result[] = $line;
        }
        return $result;
    }

    // public static function getFor($key)
    // {
    //     $data = array_filter(static::getAll(), fn ($i) => isset($i[$key]));
    //     $data = array_map(fn ($i) => $i['name'], $data);
    //     // dump($data);
    //     return $data;
    // }
}
