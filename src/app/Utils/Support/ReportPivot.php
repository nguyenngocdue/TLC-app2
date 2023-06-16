<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivot
{
    public static function transferValueOfKeys($data, $columnFields)
    {
        $newArray = array_map(function ($item) use ($columnFields) {
            foreach ($columnFields as $value) {
                $date = DateTime::createFromFormat('Y-m-d', $item[$value['fieldIndex']]);
                $reversedDate = $date->format('d-m-Y');
                $strDate = str_replace('-', '_', $reversedDate).'_'.$value['title'];
                $item[$strDate] = $item[$value['valueFieldIndex']];
            }
            return $item;
        }, $data);
        return $newArray;
    }
    public static function getLastArray($data)
    {
        $outputArrays = [];
        foreach ($data as $key => $value) {
            if ($key === "output" && is_array($value)) {
                $outputArrays[] = $value;
            } elseif (is_array($value)) {
                $nestedOutputArrays = self::getLastArray($value);
                $outputArrays = array_merge($outputArrays, $nestedOutputArrays);
            }
        }
        return $outputArrays;
    }
    public static function mergeChildrenValue($dataSource)
    {
        $data = [];
        foreach($dataSource as $value) {
            $flatten = Report::mergeArrayValues($value);
            $data = array_merge($data, $flatten);
        }
        return $data; 
    }
}
