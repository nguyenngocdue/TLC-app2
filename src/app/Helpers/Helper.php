<?php

namespace App\Helpers;

use App\Models\Attachment;
use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class Helper
{

    public static function customMessageValidation($message, $labelName)
    {
        $idx = strpos($message, 'field'); // index of "field" word in message
        $strSearch = substr($message, 4, $idx - 4);
        $newMessage = str_replace($strSearch, ("<strong>" . $labelName . " " . "</strong>"), $message);
        return $newMessage;
    }

    //OK
    public static function getFileNameNormal($file)
    {
        return $file->getClientOriginalName();
    }

    //OK
    public static function customizeSlugData($file, $tableName, $media)
    {
        $dataSource = json_decode(DB::table($tableName)->select('filename', 'extension')->get(), true);
        $nameImages = array_map(fn ($item) => array_values($item)[0], $dataSource) ?? [];
        $extensions = array_map(fn ($item) => array_values($item)[1], $dataSource) ?? [];


        // dd($dataSource, $data, $dataExtension);
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();

        $media = Attachment::get()->toArray();
        // dd($media);


        $mediaNames = array_map(fn ($item) => $item['filename'], $media);

        $separateFileName = array_keys(Helper::getName_NumericalOrderMedia($fileName))[0];
        $isValueInData = Helper::isValueInData($nameImages, $extensions, $separateFileName, $extensionFile);

        // dd($nameImages, $dataSource, $fileName, $mediaNames, $separateFileName);

        if (in_array($fileName, $mediaNames)  || $isValueInData) {
            $maxValOnDB =  Helper::getMaxNumberMediaName($nameImages, $fileName, $extensionFile);
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

    private static function getName_NumericalOrderMedia($fileName)
    {
        dd(str_ends_with("hlc-003.jpg", "."));
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

    private static function getMaxNumberMediaName($data, $fileName, $extensionFile = '')
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

    private static function isValueInData($data1, $data2, $strSearch, $extensionFile)
    {
        foreach ($data1 as $key => $value) {
            if (str_contains($value, $strSearch) && str_contains($data2[$key], $extensionFile)) {
                return true;
            }
        }
        return false;
    }



    private static function getMaxName($objNames, $fileName)
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

    private static function getTheSameNamesInDB($dataDBbySlug, $name)
    {
        // dd($dataDBbySlug, $name);
        $index = Helper::indexCharacterInString('-', $name);
        $tailName = substr($name, $index + 1, strlen($name) - $index);
        $nameInput = is_numeric($tailName) ? substr($name, 0, $index) : $name;
        // dd($nameInput, $tailName);

        $arrayNames = [];
        foreach ($dataDBbySlug as $valName) {
            if (str_contains($valName, $nameInput)) {
                $nameExtend = substr($valName, strlen($nameInput) + 1, strlen($valName) - strlen($nameInput));
                if ($nameExtend === '') {
                    $arrayNames[] = $valName;
                }
                if (is_numeric($nameExtend)) {
                    $arrayNames[] = $valName;
                }
            }
        }
        // dd($arrayNames);
        return $arrayNames;
    }

    private static function getMaxNumberName($similarNames, $nameInput)
    {
        $maxNumber = 0;
        foreach ($similarNames as $name) {
            $index = Helper::indexCharacterInString('-', $name);
            $temp = (int)substr($name,  $index >= 0 ? $index + 1 : strlen($nameInput) + 1, strlen($name) - $index);
            if (is_numeric($temp) &&  $temp > $maxNumber) {
                $maxNumber = $temp;
            }
        }
        return $maxNumber;
    }

    public static function slugNameToBeSaved($nameInput, $dataDBbyName)
    {
        if (!in_array($nameInput, $dataDBbyName)) return $nameInput;

        $similarNames =  Helper::getTheSameNamesInDB($dataDBbyName, $nameInput);
        $max =  Helper::getMaxNumberName($similarNames, $nameInput) + 1;
        $result = "$nameInput-$max";
        return $result;
    }



    public static function getDataDbByName($tableName, $keyCol = '', $valueCol = '')
    {
        $dataDB = DB::table($tableName)->get();
        $result = array_column($dataDB->toArray(), $valueCol, $keyCol);
        return $result;
    }


    public static function getColSpan($colName, $type)
    {
        $relationships = Relationships::getAllOf($type);
        $elementRel = array_values(array_filter($relationships, fn ($item) => $item['control_name'] === $colName))[0] ?? [];
        return (count($elementRel) && $tempSpan = $elementRel["radio_checkbox_colspan"]) ? $tempSpan : 3;
    }
}
