<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getDataFromPathModel($modelPath)
    {
        $insTableSource = new $modelPath();
        $tableName = $insTableSource->getTable();
        $_dataSource = DB::table($tableName)->orderBy('name')->get();
        $dataSource = [$tableName => $_dataSource];
        return  $dataSource;
    }

    public static function getDatasource($modelPath, $colName, $type = null)
    {
        if (!is_null($type)) {
            $relationship = json_decode(file_get_contents("/var/www/app/storage/json/entities/$type/relationships.json"), true);
            foreach ($relationship as $key => $value) {
                if ($value['control_name'] === $colName) {
                    $pathTableSource = $value['param_1'];
                    return Helper::getDataFromPathModel($pathTableSource);
                }
            }
        }

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
        return Helper::getDataFromPathModel($pathTableSource);
    }

    public static function customMessageValidation($message, $colName, $labelName)
    {
        $newMessage = str_replace($colName, ("<strong>" . $labelName . "</strong>"), $message);
        return $newMessage;
    }

    public static function customSlugData($file, $tableName, $orderColName, $medias)
    {


        // $fileName = 'd-1.png';
        // $medias = ['a-3.png'];
        // $data = ['a.png', 'a-1.png', 'a-2.png', 'b.png', 'b-1.png', 'b-2.png', 'b-3.png', 'c-1.png'];

        // Database
        $dataSource = json_decode(DB::table($tableName)->select($orderColName)->get(), true);
        $data = array_map(fn ($item) => array_values($item)[0], $dataSource) ?? [];
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();

        // dd($medias);





        $mediaNames = array_map(fn ($item) => $item['filename'], $medias);
        if (in_array($fileName, $data) || in_array($fileName, $mediaNames)) {
            $separateFileName = Helper::getNumericalOrderMedia($fileName); // ["d" => 0]

            $maxValOnDB =  Helper::getMaxNumberMediaName($data, array_keys($separateFileName)[0], $fileName);
            $max_ValOnTemp =  Helper::getMaxNumberMediaName($mediaNames, array_keys($separateFileName)[0], $fileName);
            $fileNameNumberMax = array_values($maxValOnDB)[0] > array_values($max_ValOnTemp)[0] ? $maxValOnDB : $max_ValOnTemp;

            $fileName =  array_keys($fileNameNumberMax)[0] . '-' . array_values($fileNameNumberMax)[0] + 1 . '.' . $extensionFile;
            return $fileName;
        }
        return $fileName;
    }

    public static function indexCharacterInString($strSearch, $str, $ofs = 0)
    {
        while (($pos = strpos($str, $strSearch, $ofs)) !== false) {
            $ofs = $pos + 1;
        }
        return $ofs - 1;
    }

    public static function getNumericalOrderMedia($fileName)
    {
        $idx1 = Helper::indexCharacterInString('-', $fileName);
        $idx2 = Helper::indexCharacterInString('.', $fileName);
        if ($idx1 >= 0) {
            $number = (int)substr($fileName, $idx1 + 1, $idx2 - $idx1 - 1);
            $strName = (string)substr($fileName, 0, $idx1);
            return [$strName => $number];
        }
        $strName = (string)substr($fileName, 0, $idx2);
        // dd($strName);
        return [$strName => 0];
    }

    public static function getMaxNumberMediaName($data, $strSearch, $file)
    {
        // dd($data, $strSearch);
        $itemHasFileName = "";

        foreach ($data as $value) {
            if (str_contains($value, $strSearch)) {
                $itemHasFileName = $value;
            }
        }
        if ($itemHasFileName === "") {
            $itemHasFileName = $file;
        };
        $fileName_number_max =  Helper::getNumericalOrderMedia($itemHasFileName);
        return $fileName_number_max;
    }
}
