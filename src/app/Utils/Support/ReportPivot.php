<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;
use DateTime;

class ReportPivot
{
    public static function transferValueOfKeys($data, $columnFields)
    {
        $newArray = array_map(function ($item) use ($columnFields) {
            $dateItems = [];
            foreach ($columnFields as $value) {
                $date = DateTime::createFromFormat('Y-m-d', $item[$value['fieldIndex']]);
                $type = 'unknown';
                if ($date) $type = 'date';
                // dump($type, $value);
                switch ($type) {
                    case 'date':
                        $reversedDate = $date->format('d-m-Y');
                        $_strDate = str_replace('-', '_', $reversedDate) . '_' . $value['fieldIndex'];
                        // $item[$_strDate] = $item[$value['valueIndexField']];
                        $dateItems[$_strDate] =  $item[$value['valueIndexField']];

                        break;
                    default:
                        $key = str_replace(' ', '_', strtolower($item[$value['fieldIndex']]));
                        $dateItems[$value['fieldIndex'].'_'.$key] =  $item[$value['valueIndexField']];
                        break;
                }
            }
            return $dateItems;
        }, $data);
        $newArray = self::sumItemsInArray($newArray);
        // dump($newArray);
        return $newArray;
    }
    public static function getLastArray($data)
    {
        $outputArrays = [];
        foreach ($data as $key => $value) {
            if ($key === "items" && is_array($value)) {
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
        foreach ($dataSource as $value) {
            $flatten = Report::mergeArrayValues($value);
            $data = array_merge($data, $flatten);
        }
        return $data;
    }
    public static function isValidDate($dateString, $dateFormat = 'Y-m-d')
    {
        $date = DateTime::createFromFormat($dateFormat, $dateString);

        return ($date && $date->format($dateFormat) === $dateString);
    }

    public static function sortItems($data, $arrayStr)
    {
        $groups = [];
        // dd($data);
        foreach ($data as $value) {
            foreach ($arrayStr as $item) {
                if (str_contains($value, $item)) {
                    $groups[$item][] = $value;
                    break;
                }
            }
            // $groups['_'][] = $value;
        }

        $group1 = array_merge(...array_values($groups));
        $group2 = array_diff($data, $group1);
        return array_merge($group2, $group1);
    }

    public static function combineArrays($keys, $values)
    {
        $combined_array = [];
        $count = min(count($keys), count($values));

        for ($i = 0; $i < $count; $i++) {
            $combined_array[$keys[$i]] = $values[$i];
        }

        return $combined_array;
    }
    private static function sumItemsInArray($newArray)
    {
        $data = [];
        foreach ($newArray as $item) {
            foreach ($item as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] = $data[$key] + $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }
}
