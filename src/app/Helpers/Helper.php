<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getDatasource($modelPath, $colName)
    {
        $instance = new $modelPath;
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
        $insTableSource = new $pathTableSource;
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

    public static function customSlugData($file, $tableName, $orderColName, $medias)
    {


        $extensionFile = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();
        $_fileName = "";

        if (count($medias) > 0) {
            $tempData = array_map(fn ($item) => $item["filename"], $medias);
            $counts = array_count_values($tempData);
            foreach ($medias as $value) {
                $n = 1;
                if ($value['filename'] === $fileName) {
                    $_fileName = str_replace('.' . $extensionFile, '-' . $counts[$fileName] . '.' . $extensionFile, $fileName);
                    while (isset($counts[$_fileName])) {
                        $_fileName = str_replace('.' . $extensionFile, '-' . $counts[$_fileName] + ++$n . '.' . $extensionFile, $fileName);
                    }
                }
            }
            return $_fileName;
        }
        $dataSource = json_decode(DB::table($tableName)->select($orderColName)->get(), true);
        if (count($dataSource) > 0) {
            $tempData = array_map(fn ($item) => array_values($item)[0], $dataSource);
            $counts = array_count_values($tempData);
            $_fileName = in_array($fileName, $tempData) ? str_replace('.' . $extensionFile, '-' .  $counts[$fileName] + 1 . '.' . $extensionFile, $fileName) : $fileName;
            $n = 1;
            while (isset($counts[$_fileName])) {
                // dd($_fileName, $counts);
                $_fileName = str_replace('.' . $extensionFile, '-' . $counts[$_fileName] + ++$n . '.' . $extensionFile, $fileName);
            }
            // dd($_fileName);
            return $_fileName;
        }

        return $fileName;
    }
}
