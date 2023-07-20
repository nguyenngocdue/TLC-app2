<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibPivotTables;
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

    private static function transferValueOfKeys($data, $columnFields, $propsColumnField)
    {
        // dd($data);
        $newArray = array_map(function ($item) use ($propsColumnField) {
            $dateItems = [];
            $dt = [];
            foreach ($propsColumnField as $values) {
                if (!isset($values['field_index']) || !isset($values['value_field_index']) || !isset($item[$values['field_index']])) continue;
                try {
                    $date = DateTime::createFromFormat('Y-m-d', $item[$values['field_index']]);
                    $type = 'unknown';
                    if ($date) $type = 'date';
                    switch ($type) {
                        case 'date':
                            $reversedDate = $date->format('d-m-y');
                            $_strDate = str_replace('-', '_', $reversedDate) . '_' . $values['field_index'];

                            $key = str_replace(' ', '_', strtolower($item[$values['field_index']]));
                            $dateItems[$_strDate] = array_merge($dt, [$values['value_field_index'] => $item[$values['value_field_index']]]);
                            break;
                        default:
                            $key = str_replace(' ', '_', strtolower($item[$values['field_index']]));
                            $dateItems[$values['field_index'] . '_' . $key] = $item[$values['value_field_index']];
                            break;
                    }
                } catch (Exception $e) {
                    continue;
                    // dd($e->getMessage(), $values);
                }
            }
            return $dateItems;
        }, $data);

        $newArray = self::sumItemsInArray($newArray);
        $newArray = self::concatKeyAndValueOfArray($newArray);
        // Check items that were duplicated in Column_Field column
        if (self::hasDuplicates($columnFields)) {
            $newColumnFields = self::markDuplicatesAndGroupKey($columnFields);
            $array = [];
            foreach ($columnFields as $field) {
                if (isset($newColumnFields[$field])) {
                    $duplicateItems = $newColumnFields[$field];
                    foreach ($duplicateItems as $fieldDup) {
                        foreach ($newArray as $key => $value) {
                            if (str_contains($key, $field)) {
                                $newKey = str_replace($field, $fieldDup, $key);
                                $array[$newKey] = $value;
                            }
                        }
                    }
                }
            }
            return $array;
        }
        // dd($newArray);
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
    // private static function sumItemsInArray($newArray)
    // {
    //     dump($newArray);
    //     $data = [];
    //     foreach ($newArray as $item) {
    //         foreach ($item as $key => $value) {
    //             if (isset($data[$key]) && is_array($value)) {
    //                 $data[$key] = self::sumArrays($data[$key], $value);
    //             } else {
    //                 $data[$key] = $value;
    //             }
    //         }
    //     }
    //     return $data;
    // }

    private static function sumItemsInArray($newArray)
    {
        // dump($newArray);
        $data = [];
        foreach ($newArray as $item) {
            foreach ($item as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] += $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }
        // dd($data);
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
        // dd($dataFilters, $linesData);
        if (empty($dataFilters)) return $linesData;
        $result = array_filter($linesData, function ($line) use ($dataFilters) {
            $lineMatchesFilter = true;
            foreach ($dataFilters as $field => $values) {
                if ($field === 'picker_date') continue;
                if (!isset($line[$field]) || !in_array($line[$field], $values)) {
                    $lineMatchesFilter = false;
                    break;
                }
            }
            return $lineMatchesFilter;
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
        $fieldsNeedToSum = $fieldsNeedToSum['value_field_index'] ?? [];
        // dd($fieldIndex, $fieldsNeedToSum);

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
        // dd($array);
        return $array;
    }

    public static function transferData($dataSource, $columnFields, $propsColumnField, $valueIndexFields)
    {
        if (empty($propsColumnField) || empty($valueIndexFields)) return $dataSource;
        // dd($dataSource, $columnFields);
        $data = array_map(
            fn ($items) => array_map(
                fn ($array) =>
                self::transferValueOfKeys($array, $columnFields, $propsColumnField),
                $items
            ),
            $dataSource
        );
        // dd($data);
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

    public static function isEmptyArray($data){
        if(is_object($data)) {
            return empty($data->toArray());
        }
        return empty($data);
    }
}
