<?php

namespace App\Utils\Support;

use DateTime;
use Illuminate\Support\Str;

class Report
{
    private static function actionCreator($reportType, $path, $singular, $mode)
    {
        return [
            'singular' => $singular,
            'mode' => $mode,
            'reportType' => $reportType,
            'path' => $path,
            // 'routeName' => $reportType . '-' . $singular . "_" . $mode,
            'name' => $reportType . '-' . $singular . "_" . $mode,
        ];
    }

    public static function getAllRoutes()
    {
        $entities = Entities::getAll();

        $result0 = [];
        foreach ($entities as $entity) {
            $entityName = Str::getEntityName($entity);
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);

            for ($i = 10; $i <= 100; $i += 10) {
                $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                $path = "App\\Http\\Controllers\\Reports\\Reports\\{$ucfirstName}_$mode";
                if (class_exists($path)) $result0[] = static::actionCreator('report', $path, $singular, $mode);

                // $path = "App\\Http\\Controllers\\PivotReports\Reports\\{$ucfirstName}_$mode";
                // if (class_exists($path)) $result0[]   = static::actionCreator('report', $path, $singular,$mode);
   
                $path = "App\\Http\\Controllers\\Reports\\Registers\\{$ucfirstName}_$mode";
                if (class_exists($path)) $result0[] = static::actionCreator('register', $path, $singular, $mode);
                $path = "App\\Http\\Controllers\\Reports\\Documents\\{$ucfirstName}_$mode";
                if (class_exists($path)) $result0[] = static::actionCreator('document', $path, $singular, $mode);
            }
        }
        $result1 = [];
        foreach ($result0 as $line) {
            $result1[$line['name']] = $line;
        }
        return $result1;
    }
    public static function getFirstItemFromChildrenArray($dataSource)
    {
        foreach ($dataSource as $key => $values) {
            $dataSource[$key] = (array)reset($values);
        }
        return $dataSource;
    }

    public static function groupArrayByKey($dataSource, $key)
    {
        $groupedArray = [];
        foreach ($dataSource as $element) {
            $eleArray = (array)$element;
            $groupedArray[$eleArray[$key]][] = $eleArray;
        }
        // dd($groupedArray, $dataSource);
        return $groupedArray;
    }


    public static function groupArrayByKey2($dataSource, $key, $returnKey, $returnValue)
    {
        $groupedArray = [];
        foreach ($dataSource as $element) {
            $eleArray = (array)$element;
            $groupedArray[$eleArray[$key]][$eleArray[$returnKey]] = $eleArray[$returnValue];
        }
        // dd($groupedArray, $dataSource);
        return $groupedArray;
    }

    public static function assignKeyByKey($dataSource, $keyName)
    {
        $array = [];
        foreach ($dataSource as $element) {
            $element = is_object($element) ? (array)$element : $element;
            $array[$element[$keyName]] = $element;
        }
        return $array;
    }
    public static function mergeArrayValues($grouped_array)
    {
        $result = [];
        foreach (array_keys($grouped_array) as $key) {
            $result[] = array_merge(...$grouped_array[$key]);
        }
        return $result;
    }
    public static function slugName($string)
    {
        $strLower = strtolower($string);
        return preg_replace('/[[:space:]-]+/', "_", $strLower);
    }
    public static function makeTitle($string)
    {
        return ucwords(str_replace('_', ' ', $string));
    }

    public static function pressArrayTypeAllItems($dataSource)
    {
        $array = [];
        foreach ($dataSource as $key => $value) {
            $array[] = (array)$value;
        }
        return $array;
    }
    public static function replaceAndUcwords($string)
    {
        $str = str_replace('_', " ", $string);
        $str = ucwords($str, '-');
        return  ucwords(str_replace('_', " ", $str));
    }
    public static function isNullModeParams($modeParams)
    {
        // dd($modeParams);
        return count(array_filter($modeParams, fn ($value) => !is_null($value))) <= 0;
    }
    public static function getViewName($str)
    {
        return 'param-' . str_replace('_', '-', $str);
    }
    public static function transferValueOfKey($array, $key, $value)
    {
        $newArray = array_map(function ($item) use ($key, $value) {
            $date = DateTime::createFromFormat('Y-m-d', $item[$key]);
            $reversedDate = $date->format('d-m-Y');
            $strDate = str_replace('-', '_', $reversedDate);
            $item[$strDate] = $item['time_sheet_hours'];
            return $item;
        }, $array);
        return $newArray;
    }

    public static function explodePickerDate($pickerDate)
    {
        $pickerDate = array_map(fn ($item) => trim($item), explode('-', $pickerDate));
        $startDate = $pickerDate[0] ?? '01/01/2021';
        $endDate = $pickerDate[1] ?? '01/02/2021';
        return [$startDate, $endDate];
    }

    public static function formatDateString($strDate, $typeFormat = 'Y-m-d')
    {
        $strDate = str_replace(['-', '_'], '/', $strDate);
        $dateTime = DateTime::createFromFormat('d/m/Y', $strDate);
        if (!$dateTime) return false;
        if ($dateTime) {
            return $dateTime->format($typeFormat);
        }
        return DateTime::createFromFormat('Y/m/d', $strDate)->format($typeFormat);
    }

    public static function retrieveDataByIndex($array, $key, $reverse = false, $type = 'key')
    {
        $idx = array_search($key, array_values($array));
        if ($type == 'key') {
            $idx = array_search($key, array_keys($array));
        }
        if ($reverse) return array_slice($array, 0, $idx + 1);
        $data = array_slice($array, $idx + 1, count($array) - $idx);
        // sort($data);
        return $data;
    }

    public static function dataWithoutNull($data)
    {
        return array_filter($data, function ($value) {
            return $value !== null && $value != '';
        });
    }

    public static function sumAndMergeKeys(array $array)
    {
        $result = [];

        foreach ($array as $item) {
            foreach ($item as $key => $value) {
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }
                $result[$key] += $value;
            }
        }

        return $result;
    }

    public static function sumAndMergeNestedKeys($array)
    {
        $result = [];
        foreach ($array as $key => $subArray) {
            $mergedArray = [];
            foreach ($subArray as $item) {
                foreach ($item as $subKey => $value) {
                    if (!isset($mergedArray[$subKey])) {
                        $mergedArray[$subKey] = 0;
                    }
                    $mergedArray[$subKey] += $value;
                }
            }
            $result[$key] = $mergedArray;
        }
        return $result;
    }

    public static function addMissingMonths($months)
    {
        $fullMonths = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

        $missingMonths = [];
        $year = null;
        if (!empty($months)) {
            $year = substr($months[0], 0, 4);
        }
        foreach ($fullMonths as $month) {
            $fullMonth = "$year-$month";
            if (!in_array($fullMonth, $months)) {
                $missingMonths[] = $fullMonth;
            }
        }

        return array_merge($months, $missingMonths);
    }

    public static function sortByKey($data, $key, $isDateTime = false) {
        usort($data, function($a, $b) use ($key, $isDateTime) {
            if ($isDateTime) {
                return strtotime($a[$key]) <=> strtotime($b[$key]);
            } else {
                if (!isset($a[$key]) || !isset($b[$key])) return 0;
                return strcmp($a[$key], $b[$key]);
            }
        });
        return $data;
    }


    public static function removeEmptyElements($array)
    {
        foreach ($array as $key => $items) {
            if ($items === "") continue;
            if (is_array($items)) {
                if (!$items) continue;
                $arr = array_filter($items, function ($value) {
                    return $value !== "";
                });
                $array[$key] = $arr;
            }
        }
        return $array;
    }

    public static function removeNullValuesFromArray($inputArray) {
        // if (is_null(reset($inputArray)) && count($inputArray) === 1) return [];
        return array_filter($inputArray, fn($value) => $value !== null);
    }

}
