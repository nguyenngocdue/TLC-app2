<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibPivotTables;
use Illuminate\Support\Str;
use DateTime;
use Exception;

class ReportPivot
{
    private static function transferValueOfKeys($data, $columnFields, $valueIndexFields)
    {
        // dd(array_column($data, 'sub_project_id'));
        $newArray = array_map(function ($item) use ($columnFields, $valueIndexFields) {
            $dateItems = [];
            foreach ($columnFields as $value) {
                if (!isset($value['fieldIndex']) || !isset($value['valueIndexField']) || !isset($item[$value['fieldIndex']])) continue;
                try {
                    $date = DateTime::createFromFormat('Y-m-d', $item[$value['fieldIndex']]);
                    $type = 'unknown';
                    if ($date) $type = 'date';
                    switch ($type) {
                        case 'date':
                            $reversedDate = $date->format('d-m-y');
                            $_strDate = str_replace('-', '_', $reversedDate) . '_' . $value['fieldIndex'];
                            $dateItems[$_strDate] =  $item[$value['valueIndexField']];
                            break;
                        default:
                            $key = str_replace(' ', '_', strtolower($item[$value['fieldIndex']]));
                            $array = [];
                            foreach ($valueIndexFields as $field) {
                                if (!$field) continue;
                                $array[$field] = $item[$field];
                            }
                            // dd($value);
                            $dateItems[$value['fieldIndex'] . '_' . $key] = $array;
                            break;
                    }
                } catch (Exception $e) {
                    dd($e->getMessage(), $value);
                }
            }
            return $dateItems;
        }, $data);
        // $newArray = self::sumItemsInArray01($newArray);
        // dump($newArray);
        $newArray = self::sumItemsInArray($newArray);
        $newArray = self::concatKeyAndValueOfArray($newArray);
        return $newArray;
    }
    public static function getLastArray($data, $fieldsNeedToSum = [])
    {
        $outputArrays = [];
        foreach ($data as $key => $value) {
            if ($key === "items" && is_array($value)) {
                //Sum the results of Value_Index_Fields that has the same value in a field
                //Sum values before using Value Index Fields
                $outputArrays[] = self::sumFieldsHaveTheSameValue($value, $fieldsNeedToSum);
            } elseif (is_array($value)) {
                $nestedOutputArrays = self::getLastArray($value, $fieldsNeedToSum);
                $outputArrays = array_merge($outputArrays, $nestedOutputArrays);
            }
        }
        // dd($outputArrays);
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
    private static function sumItemsInArray01($newArray)
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


    private static function sumArrays($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            if (isset($array2[$key])) {
                $result[$key] = $value + $array2[$key];
            }
        }
        return $result;
    }

    private static function sumItemsInArray($newArray)
    {
        // dump($newArray);
        $data = [];
        foreach ($newArray as $item) {
            foreach ($item as $key => $value) {
                if (isset($data[$key]) && is_array($value)) {
                    $data[$key] = self::sumArrays($data[$key], $value);
                } else {
                    $data[$key] = $value;
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
            if (!is_array($item))  return $newArray;
            foreach ($item as $k2 => $value) {
                $data[$k1 . '_' . $k2] = $value;
            }
        }
        return $data;
    }


    public static function reduceDataByFilterColumn($linesData, $conditions)
    {
        $conditions = Report::dataWithoutNull($conditions);
        $result = array_filter($linesData, function ($data) use ($conditions) {
            foreach ($conditions as $field => $values) {
                if (!isset($data[$field]) || !in_array($data[$field], $values)) {
                    return false;
                }
            }
            return true;
        });
        return $result;
    }
    public static function groupBy($lineData, $rowFields)
    {
        // if (empty($rowFields)) return $lineData;
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
        }
        // dd($dataOutput);
        return $dataOutput;
    }

    private static function sumFieldsHaveTheSameValue($data, $fieldsNeedToSum)
    {
        $array = [];
        $fieldIndex = $fieldsNeedToSum['fieldIndex'] ?? [];
        $fieldsNeedToSum = $fieldsNeedToSum['valueIndexField'] ?? [];
        // dump($fieldIndex, $fieldsNeedToSum);

        foreach ($data as $item) {
            $found = false;
            foreach ($fieldsNeedToSum as $valueIndexField) {
                if (!$valueIndexField) continue;
                foreach ($array as  &$value) {
                    // if (!empty($fieldsNeedToSum)) continue;
                    // if (!empty($fieldIndex) && !empty($fieldsNeedToSum)) continue;
                    $check = false;
                    foreach ($fieldIndex as $field) {
                        if(!$field) continue;
                        if ($item[$field] === $value[$field]) {
                            $check = true;
                        }
                    }
                    if ($check) {
                        $found = true;
                        $value[$valueIndexField] += $item[$valueIndexField];
                        break;                 
                    }
                }
                
            }
            if (!$found) $array[] = $item;
        }
        // dd($array);
        return $array;
    }

    public static function transferData($dataSource, $propsColumnField, $valueIndexFields)
    {
        if (empty($propsColumnField) || empty($valueIndexFields)) return $dataSource;
        $data = array_map(
            fn ($items) => array_map(
                fn ($array) =>
                self::transferValueOfKeys($array, $propsColumnField, $valueIndexFields),
                $items
            ),
            $dataSource
        );
        return $data;
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

    public static function groupItemsByFirstWord($data) {
        $groupedItems = [];
      
        foreach ($data as $item) {
          $nameParts = explode('_', $item);
          $firstWord = $nameParts[0];
          if (!isset($groupedItems[$firstWord])) {
            $groupedItems[$firstWord] = [];
          }
          $groupedItems[$firstWord][] = $item;
        }
        return $groupedItems;
      }
}
