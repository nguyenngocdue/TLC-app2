<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getDatasource($id = 0, $modelPath, $colName)
    {
        $eloquentParam = $modelPath->eloquentParams;
        // dd($eloquentParam, $colName);
        $keyNameEloquent = "";
        foreach ($eloquentParam as $key => $value) {
            if (isset(array_flip($value)[$colName])) {
                $keyNameEloquent = $key;
                break;
            }
        }
        if ($keyNameEloquent === "") return $colName;
        $pathTableSource = $eloquentParam[$keyNameEloquent][1];
        $insTableSource = new $pathTableSource;
        $tableName = $insTableSource->getTable();
        $_dataSource = DB::table($tableName)->orderBy('name')->get();
        $currentEntity = is_null($modelPath::find($id)) ? "" : $modelPath::find($id)->getAttributes();
        $dataSource = [$tableName => json_decode($_dataSource), 'currentEntity' => $currentEntity];

        return $dataSource;
    }

    public static function customMessageValidation($message, $colName, $labelName)
    {
        $newMessage = str_replace($colName, ("<strong>" . $labelName . "</strong>"), $message);
        return $newMessage;
    }
}
