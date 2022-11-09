<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getDatasource($modelPath, $colName)
    {
        $instance = new $modelPath();
        $eloquentParam = $instance->eloquentParams;

        $keyNameEloquent = "";
        foreach ($eloquentParam as $key => $value) {
            if (in_array($colName, $value)) {
                $keyNameEloquent = $key;
                break;
            }
        }
        if ($keyNameEloquent === "") return $colName;

        $pathTableSource = $eloquentParam[$keyNameEloquent][1];
        $insTableSource = new $pathTableSource();
        $tableName = $insTableSource->getTable();

        $_dataSource = DB::table($tableName)->orderBy('name')->get();
        $dataSource = [$tableName => $_dataSource];

        return $dataSource;
    }

    public static function customMessageValidation($message, $colName, $labelName)
    {
        $newMessage = str_replace($colName, ("<strong>" . $labelName . "</strong>"), $message);
        return $newMessage;
    }
}
