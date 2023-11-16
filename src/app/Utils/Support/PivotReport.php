<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibPivotTables;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Str;
use DateTime;
use Exception;

class PivotReport
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
                $path = "App\\Http\\Controllers\\PivotReports\\Reports\\{$ucfirstName}_$mode";
                if (class_exists($path)) $result0[] = static::actionCreator('report-pivot', $path, $singular, $mode);
            }
        }

        $result1 = [];
        foreach ($result0 as $line) {
            $result1[$line['name']] = $line;
        }
        return $result1;
    }

    private static function transferValueOfKeys2($data, $columnFields)
    {
        // dd($columnFields, $data);
        $newArray = array_map(function ($item) use ($columnFields) {
            $dateItems = [];
            $dt = [];
            foreach ($columnFields as $key => $values) {
                if (!isset($values->value_index)) continue;
                try {
                    $date = DateTime::createFromFormat('Y-m-d', $item[$key]);
                    $type = 'unknown';
                    if ($date) $type = 'date';
                    switch ($type) {
                        case 'date':
                            $reversedDate = $date->format('d-m-y');
                            $_strDate = str_replace('-', '_', $reversedDate) . '_' . $key;

                            $key = str_replace(' ', '_', strtolower($item[$key]));
                            $dateItems[$_strDate] = array_merge($dt, [$values->value_index => $item[$values->value_index]]);
                            break;
                        default:
                            $keyNumber = str_replace(' ', '_', strtolower($item[$key]));
                            $dateItems[$key . '_' . $keyNumber] = $item[$values->value_index];
                            break;
                    }
                } catch (Exception $e) {
                    continue;
                    // dd($e->getMessage(), $values);
                }
            }
            return $dateItems;
        }, $data);
        return $newArray;
    }


    public static function getLastArray($data, $columnFields = [], $isRaw=false)
    {
        $outputArrays = [];
        foreach ($data as $key => $value) {
            if ($key === "items" && is_array($value)) {
                if($isRaw) {
                    if(count($value) > 1) {
                        // add all of the items in the array 
                        foreach ($value as $item) $outputArrays[][]= $item;
                    } else{
                        $outputArrays[] = $value;
                    }
                }else{
                    //Sum the results of Value_Index_Fields that has the same value in a field
                    //Sum values before using Value Index Fields
                    $outputArrays[] = self::sumFieldsHaveTheSameValue($value, $columnFields);

                }
            } elseif (is_array($value)) {
                $nestedOutputArrays = self::getLastArray($value, $columnFields,$isRaw);
                $outputArrays = array_merge($outputArrays, $nestedOutputArrays);
            }
        }
        // dd($data,$outputArrays);
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
    public static function isValidDate($dateStr, $type = 'd-m-y')
    {
        $dateObj = DateTime::createFromFormat($type, $dateStr);
        if ($dateObj && $dateObj->format($type) === $dateStr) return true;
        return false;
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

    public static function groupDataFields($dataFields, $dataAggregation)
    {
        $array = [];
        if (!isset($dataFields['field_names'])) return [];
        $x = $dataFields['field_names'];
        foreach ($dataAggregation as $key => $value) {
            $array[$x[$key]]['name'] = $x[$key];
            $array[$x[$key]]['data_index'] = $value . '_' . $x[$key];
            $array[$x[$key]]['type_operator'] = $value;
            $array[$x[$key]]['title_override'] = ($t = $dataFields['field_titles'][$key]) ? $t : $value . '_' . $x[$key];
        }
        return $array;
    }

    private static function sumItemsInArray($newArray)
    {
        // dd($newArray);
        $data = [];
        foreach ($newArray as $item) {
            if(is_array($item)) {
                foreach ($item as $key => $value) {
                    if (isset($data[$key])) {
                        $data[$key] += $value;
                    } else {
                        $data[$key] = $value;
                    }
                }
            }
        }
        return $data;
    }

    private static function countItemsInArray($newArray)
    {
        $data = [];
        foreach ($newArray as $item) {
            foreach ($item as $key => $value) {
                // dd($key);
                if (isset($data[$key])) {
                    $num = $data[$key][key($value)];
                    $data[$key] = [key($value) => $num + count($value)];
                } else {
                    // dd($value);
                    $data[$key] = [key($value) => count($value)];
                }
            }
        }
        return $data;
    }

    private static function concatKeyAndValueOfArray($newArray)
    {
        $data = [];
        foreach ($newArray as $k1 =>  $item) {
            // dump($k1);
            if (!is_array($item)) $data[$k1] = $item;
            else {
                foreach ($item as $k2 => $value) {
                    $data[$k1 . '_' . $k2] = $value;
                }
            }
        }
        // dd($data);
        return $data;
    }

    public static function reduceDataByFilterColumn($linesData, $dataFilters)
    {
        if (empty($dataFilters)) return $linesData;
        $result = array_filter($linesData, function ($line) use ($dataFilters) {
            $lineMatchesFilter = true;
            foreach ($dataFilters as $field => $values) {
                if ($field === 'picker_date') continue;
                if($field === 'status') continue;
                if (!isset($line[$field]) || !in_array($line[$field], $values)) {
                    $lineMatchesFilter = false;
                    break;
                }
            }
            return $lineMatchesFilter;
        });
        // dd($result);
        return $result;
    }
    public static function groupBy($lineData, $rowFields)
    {
        if (empty($lineData)) return [];
        $dataOutput = [];
        foreach ($lineData as $line) {
            // Get the values of fields in $rowFields
            $params = [];
            foreach ($rowFields as $param) {
                if (isset($line[$param])) $params[$param] = $line[$param];
                else $params[$param] = null;
            }
            $nestedArray = &$dataOutput;
            foreach ($params as $paramValue) {
                // Create nested arrays in $dataOutput based on the values of fields in $rowFields
                if (!isset($nestedArray[$paramValue])) {
                    $nestedArray[$paramValue] = [];
                }
                $nestedArray = &$nestedArray[$paramValue];
            }
            if (!isset($nestedArray['items'])) $nestedArray['items'] = [];
            $nestedArray['items'][] = $line;
            // dump($nestedArray);
        }
        // dump($dataOutput);
        return $dataOutput;
    }

    private static function sumFieldsHaveTheSameValue($data, $fieldsNeedToSum)
    {
        // dd($data);
        $array = [];
        $fieldIndex = $fieldsNeedToSum['field_indexes'] ?? [];
        $fieldsNeedToSum = $fieldsNeedToSum['value_field_indexes'] ?? [];
        // dump($data);

        foreach ($data as $item) {
            $found = false;
            foreach ($fieldsNeedToSum as $valueIndexField) {
                if (!$valueIndexField) continue;
                foreach ($array as  &$value) {
                    $check = false;
                    foreach ($fieldIndex as $field) {
                        if (!$field || !isset($item[$field])) continue;
                        if ($item[$field] === $value[$field]) {
                            $check = true;
                        }
                    }
                    if ($check) {
                        $found = true;
                        if (!isset($item[$valueIndexField])) continue;
                        $value[$valueIndexField] += $item[$valueIndexField];
                        break;
                    }
                }
            }
            if (!$found) $array[] = $item;
        }
        return $array;
    }


    public static function transferData2($dataSource, $columnFields)
    {
        $data = array_map(
            fn ($items) => array_map(
                function($array) use ($columnFields) {
                    $newArray = self::transferValueOfKeys2($array, $columnFields);
                    foreach ($columnFields as $field => $valColField) {
                        $agg = $valColField->aggregation ?? 'sum';
                        switch ($agg) {
                            case 'sum':
                                $newArray = self::sumItemsInArray($newArray);
                                break;
                            case  'count':
                                $newArray = self::countItemsInArray($newArray);
                                break;
                            default:
                                break;
                        }
                    }
                    $newArray = self::concatKeyAndValueOfArray($newArray);
                    return $newArray;
                },$items
            ),
            $dataSource
        );
        return $data;
    }

    public static function getDatesBetween($startDate, $endDate)
    {
        $dateList = array();
        // Convert start and end dates to DateTime objects
        $start = DateTime::createFromFormat('d/m/Y', $startDate);
        $end = DateTime::createFromFormat('d/m/Y', $endDate);
        // Include the end date in the range
        $end = $end->modify('+1 day');
        // Iterate over the range of dates
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        foreach ($dateRange as $date) {
            $dateList[] = $date->format('d/m/Y');
        }
        return $dateList;
    }


    public static function isSaturdayOrSunday($dateString)
    {
        $date = DateTime::createFromFormat("d/m/Y", $dateString);
        // dump($dateString);
        if ($date === false) return '';
        $dayOfWeek = $date->format("N"); // Retrieve the day of the week as a string
        if ($dayOfWeek >= 6) return true;
        return false;
    }
    public static function findPosition($string, $searchStr, $positionNumber)
    {
        $position = 0;
        $count = 0;
        while (($position = strpos($string, $searchStr, $position))) {
            $position++;
            $count++;
            if ($count === $positionNumber) {
                return $position;
            }
        }
        return -1;
    }
    public static function isStringInItemsOfArray($data, $value)
    {
        foreach ($data as $field) {
            if (!$field) continue;
            if (str_contains($value, $field)) {
                return true;
            }
        }
        return false;
    }

    public static function groupItemsByString($data, $type = 'first')
    {
        $groupedItems = [];
        // dd($data);
        foreach ($data as $item) {
            $nameParts = explode('_', $item);
            $text = $nameParts[0];
            if ($type === 'end') {
                $thirdUnderlined = PivotReport::findPosition($item, '_', 3);
                $text = substr($item, $thirdUnderlined);
            }
            if (!isset($groupedItems[$text])) {
                $groupedItems[$text] = [];
            }
            $groupedItems[$text][] = $item;
        }
        return $groupedItems;
    }


    public static function separateValueByStringInArray($data, $string1, $string2 = ":", $index = 0)
    {
        $arrayFields = [];
        foreach ($data as $value) {
            if (str_contains($value, $string1)) {
                $field = explode($string1, $value, 2)[$index];
                $arrayFields[] = $field;
            } else {
                $field = explode($string2, $value, 2)[$index];
                $arrayFields[] = $field;
            }
        }
        return $arrayFields;
    }
    public static function markDuplicatesAndGroupKey($arr)
    {
        $counts = array();
        $groupedArr = array();
        foreach ($arr as $value) {
            if ($value == "") continue;

            if (!isset($counts[$value])) {
                $counts[$value] = 2;
                $groupedArr[$value] = array($value);
            } else {
                $groupedArr[$value][] = $value . "[" . $counts[$value] . "]";
                $counts[$value]++;
            }
        }
        return $groupedArr;
    }

    public static function markDuplicatesAndGroupKey2($arr)
    {
        $counts = array();
        $groupedArr = array();
        foreach ($arr as $value) {
            if ($value == "") continue;

            if (!isset($counts[$value])) {
                $counts[$value] = 2;
                $groupedArr[$value] = array($value);
            } else {
                $groupedArr[$value][] = $value . "[" . $counts[$value] . "]";
                $counts[$value]++;
            }
        }
        return $groupedArr;
    }

    public static function markDuplicates($arr)
    {
        $counts = array();
        $groupedArr = array();
        foreach ($arr as $value) {
            if ($value == "") continue;
            if (!isset($counts[$value])) {
                $counts[$value] = 2;
                $groupedArr[] = $value;
            } else {
                $groupedArr[] = $value . "[" . $counts[$value] . "]";
                $counts[$value]++;
            }
        }
        return $groupedArr;
    }

    public static function hasDuplicates($array)
    {
        $counts = array_count_values($array);
        foreach ($counts as $value) {
            if ($value > 1) {
                return true;
            }
        }
        return false;
    }

    public static function hasDuplicates2($array)
    {
        $counts = array_count_values(((array)$array));

        foreach ($counts as $value) {
            if ($value > 1) {
                return true;
            }
        }
        return false;
    }

    public static function countItems($array)
    {
        $itemCounts = array();
        foreach ($array as $item) {
            if (isset($itemCounts[$item])) {
                $itemCounts[$item]++;
            } else {
                $itemCounts[$item] = 1;
            }
        }
        return $itemCounts;
    }
    public static function groupSimilarStrings($arr)
    {
        $groupedArr = array();

        foreach ($arr as $value) {
            $key = preg_replace('/_[^_]*$/', '', $value);

            if (isset($groupedArr[$key])) {
                $groupedArr[$key][] = $value;
            } else {
                $groupedArr[$key] = array($value);
            }
        }

        return $groupedArr;
    }

    public static function isEmptyArray($data)
    {
        if (is_object($data)) {
            return empty($data->toArray());
        }
        return empty($data);
    }

}
