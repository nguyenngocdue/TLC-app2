<?php

namespace App\Helpers;

use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Facades\DB;

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


    public static function indexCharacterInString($strSearch, $str, $ofs = 0)
    {
        while (($pos = strpos($str, $strSearch, $ofs)) !== false) {
            $ofs = $pos + 1;
        }
        return $ofs - 1;
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
