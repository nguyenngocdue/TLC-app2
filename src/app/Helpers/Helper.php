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

        $instance = new $modelPath;
        $eloquentParam = $instance->eloquentParams;

        if (!is_null($type)) {
            $relationship = json_decode(file_get_contents("/var/www/app/storage/json/entities/$type/relationships.json"), true);
            foreach ($relationship as $value) {
                if ($value['control_name'] === $colName) {
                    $pathTableSource =  $eloquentParam[$value['relationship']][1] ?? [];
                    return Helper::getDataFromPathModel($pathTableSource);
                }
            }
            return $colName;
        }

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

    public static function customizeSlugData($file, $tableName, $media)
    {
        $dataSource = json_decode(DB::table($tableName)->select('filename', 'extension')->get(), true);
        $data = array_map(fn ($item) => array_values($item)[0], $dataSource) ?? [];
        $dataExtension = array_map(fn ($item) => array_values($item)[1], $dataSource) ?? [];

        // dd($dataSource, $data, $dataExtension);
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();

        $mediaNames = array_map(fn ($item) => $item['filename'], $media);

        $separateFileName = array_keys(Helper::getName_NumericalOrderMedia($fileName))[0];
        $isValueInData = Helper::isValueInData($data, $dataExtension, $separateFileName, $extensionFile);

        if (in_array($fileName, $mediaNames)  || $isValueInData) {
            $maxValOnDB =  Helper::getMaxNumberMediaName($data, $fileName, $extensionFile);
            $max_ValOnTemp =  Helper::getMaxNumberMediaName($mediaNames, $fileName, $extensionFile);
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

    public static function getMaxNumberMediaName($data, $fileName, $extensionFile)
    {
        $idx = Helper::indexCharacterInString('.', $fileName);
        $fname = substr($fileName, 0,  $idx);

        $similarNames = [];
        foreach ($data as $value) {
            $separateName = Helper::getName_NumericalOrderMedia($value);
            $_idx = Helper::indexCharacterInString('.', $value);
            $valExtension = substr($value, $_idx + 1, strlen($value) - $_idx);
            if ((string)array_keys($separateName)[0] === $fname && $valExtension === $extensionFile) {
                $similarNames[] = $value;
            }
        }
        $fileName = Helper::getMaxName($similarNames, $fileName);

        $fileName_number_max =  Helper::getName_NumericalOrderMedia($fileName);
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
    public static function removeItemsByKeysArray($originaleArray, $keysArray)
    {
        foreach ($keysArray as $value) {
            unset($originaleArray[$value]);
        }
        return $originaleArray;
    }
    public static function getColNamesbyCondition($props, $nameCotrol, $nameType = "column_type", $valnameControl = "", $valNameType = "", $typeCheck = 'type1')
    {
        switch ($typeCheck) {
            case ('type1'): {
                    $type = array_filter($props, fn ($prop) => $prop[$nameCotrol] === $valnameControl && $prop[$nameType] === $valNameType);
                    $colNamebyControls = array_values(array_map(fn ($item) => $item['column_name'], $type));
                    return $colNamebyControls;
                }
            case ('type2'): {
                    $type = array_filter($props, fn ($prop) => $prop[$nameCotrol] != '');
                    $colNamebyControls = array_values(array_map(fn ($item) => $item, $type));
                    return $colNamebyControls;
                }
        }
    }
    public static function getMaxName($objNames, $fileName)
    {
        $_fileName = $fileName;
        foreach ($objNames as $value) {
            $maxName = 0;
            $separateName = Helper::getName_NumericalOrderMedia($value);
            if ((int)array_values($separateName)[0] > $maxName) {
                $maxName = (int)array_values($separateName)[0];
                $_fileName = $value;
            }
        }
        return $_fileName;
    }
}
