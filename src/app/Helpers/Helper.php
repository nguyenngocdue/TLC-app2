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



    public static function customSlugData($file, $tableName, $orderColName, $medias)
    {
        $dataSource = json_decode(DB::table($tableName)->select('filename', 'extension')->get(), true);
        $data = array_map(fn ($item) => array_values($item)[0], $dataSource) ?? [];
        $dataExtension = array_map(fn ($item) => array_values($item)[1], $dataSource) ?? [];

        // dd($dataSource, $data, $dataExtension);
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();

        $mediaNames = array_map(fn ($item) => $item['filename'], $medias);

        $separateFileName = array_keys(Helper::getName_NumericalOrderMedia($fileName))[0];
        $isValueInData = Helper::isValueInData($data, $dataExtension, $separateFileName, $extensionFile);

        if (in_array($fileName, $data) || in_array($fileName, $mediaNames)  || $isValueInData) {

            $maxValOnDB =  Helper::getMaxNumberMediaName($data, $separateFileName, $fileName, $extensionFile);
            $max_ValOnTemp =  Helper::getMaxNumberMediaName($mediaNames, $separateFileName, $fileName, $extensionFile);
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

    public static function getName_NumericalOrderMedia($fileName)
    {
        $idx1 = Helper::indexCharacterInString('-', $fileName);
        $idx2 = Helper::indexCharacterInString('.', $fileName);
        $strName = (string)substr($fileName, 0, $idx1);
        if ($idx1 >= 0) {
            $number = substr($fileName, $idx1 + 1, $idx2 - $idx1 - 1);
            if (!is_numeric($number)) {
                $strName = $strName . substr($fileName, $idx1, $idx2 - $idx1);
                return [$strName => 0];
            }
            return [$strName => $number];
        }
        $strName = (string)substr($fileName, 0, $idx2);

        return [$strName => 0];
    }

    public static function getMaxNumberMediaName($data, $strSearch, $file, $extensionFile)
    {
        $itemHasFileName = "";

        foreach ($data as $value) {
            if (str_contains($value, $strSearch) && str_contains($value, $extensionFile)) {
                $itemHasFileName = $value;
            }
        }
        if ($itemHasFileName === "") {
            $itemHasFileName = $file;
        };
        $fileName_number_max =  Helper::getName_NumericalOrderMedia($itemHasFileName);
        return $fileName_number_max;
    }

    public static function isValueInData($data1, $data2, $strSearch, $extensionFile)
    {
        foreach ($data1 as $key => $value) {
            if (str_contains($value, $strSearch) && str_contains($data2[$key], $extensionFile)) {
                return true;
            }
        }
        return false;
    }
}
