<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use App\Utils\Support\PivotReportDataFields;
use Exception;
use Illuminate\Support\Facades\DB;

class PivotTable extends Controller
{

    use TraitLibPivotTableDataFields;
    use ColumnsPivotReport;
    public function __construct(
        private $modeType = '',
        private $dataSource = [],
        private $itemsSelected = [],
    ) {
    }


    private function attachToDataSource($processedData, $calculatedData, $transferredData, $rowFields)
    {
        $dataOutput = [];
        foreach ($processedData as $k1 => $items) {
            foreach ($items as $k2 => $item) {
                $dt = isset($calculatedData[$k1][$k2]) ? $calculatedData[$k1][$k2] : [];
                // $dt2 = isset($transferredData[$k1][$k2]) ?$transferredData[$k1][$k2] : [];
                $dataOutput[] = array_merge($dt, reset($item), $transferredData[$k1][$k2]);
            };
        }
        if (!$rowFields && $calculatedData) {
            $data1 = array_map(function ($item) use ($calculatedData) {
                return array_merge($calculatedData[0], $item);
            }, $dataOutput);
            return $data1;
        }
        return $dataOutput;
    }

    private function getDataFromTables($tableIndex)
    {
        $dataTables = [];
        foreach (array_values($tableIndex) as $name){
            if(!$name) continue;
            try {
                $array = DB::table($name)->select('id', 'name', 'description')->get()->toArray();
                $dataTables[$name]  = array_combine(array_column($array, 'id'), $array);;

            } catch (\Exception $e) {
                $array = DB::table($name)->select('id', 'name')->get()->toArray();
                $dataTables[$name]  = array_combine(array_column($array, 'id'), $array);
            }
        }
        return $dataTables;
    }

    private function attachInfoToDataSource($tables, $processedData, $allDataFields)
    {
        [$rowFields, $bidingRowFields,,, $bidingColumnFields,,,,,,,,,] =  $allDataFields;

        foreach ($processedData as &$items) {
            foreach ($items as $key => $id) {
                if (in_array($key, $rowFields)) {
                    try {
                        $infoAttr = $bidingRowFields[$key];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        $fieldName = $key . '_' . $tableName . '_' . $attributeName;
                        $items[$fieldName] = $tables[$tableName][$id]->$attributeName;
                    } catch (Exception $e) {
                        $items[$key] = $id;
                        // dump($e->getMessage());
                    }
                } else {
                    if (!str_contains($key, '_id')) continue;
                    $indexString = strrpos($key, '_id_');
                    $p1 = strpos($key, '_', $indexString + 4);
                    $str1 = substr($key, 0, $indexString + 4);
                    $str2 = substr($key, $p1, strlen($key));
                    $id = str_replace([$str1, $str2], '', $key);
                    if (str_contains($key, '_id[')) {
                        $indexString = strrpos($key, '_id[');
                        $lastBracket = strrpos($key, ']');
                        $p1 = strpos($key, '_', $indexString + ($lastBracket - $indexString + 2));
                        $str1 = substr($key, 0, $indexString + ($lastBracket - $indexString + 2));
                        $str2 = substr($key, $p1, strlen($key));
                        $id = str_replace([$str1, $str2], '', $key);
                    }
                    if (is_numeric(trim($id, '_')) && $id) {
                        $attr = substr($key, 0, $indexString + 3);
                        $infoAttr = $bidingColumnFields[$attr];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        // dd($tables, $tableName, $attributeName);
                        $name = $id;
                        if (isset($tables[$tableName])) {
                            $name = $tables[$tableName][$id]->$attributeName;
                        }
                        $items['top_title_column'][$key] = $name;
                    }
                }
            }
        }
        // dd($processedData);
        return $processedData;
    }

    private function sortByData($sortByColumn)
    {
        $orders = [];
        // if(is_null($sortByColumn) || empty($sortByColumn)) return [];
        array_map(function ($item) use (&$orders) {
            $ex = explode(':', $item);
            $key = $ex[0];
            $strKey = trim(str_replace(['(', '.'], '_', $key), ')');
            $order = isset($ex[1]) ? strtolower($ex[1]) : 'asc';
            $orders[$strKey] = $order;
        }, $sortByColumn);
        // dd($orders);
        return $orders;
    }

    public function sortLinesData($dataOutput, $allDataFields)
    {
        // dd($dataOutput);
        [,,,,,,, $sortBy,,,,,,] = $allDataFields;
        // dd($allDataFields);
        // if (!$this->getDataFields($dataOutput)) return collect($dataOutput);
        $sortOrders = $this->sortByData($sortBy);
        uasort($dataOutput, function ($item1, $item2) use ($sortOrders) {
            foreach ($sortOrders as $field => $sortOrder) {
                if (!$field) continue;
                try {
                    $comparison = $item1[$field] <=> $item2[$field];
                    if ($comparison) {
                        return ($sortOrder === 'asc') ? $comparison : -$comparison;
                    }
                } catch (\Exception $e) {
                    // dd($e->getMessage() . ' in "Row_Fields" column');
                }
            }
            return 0;
        });
        return collect($dataOutput);
    }

    // protected function changeValueData($dataSource)
    // {
    //     $dataSource = array_slice($dataSource->toArray(), 0, 10000000);
    //     [$rowFields,,,,,, $dataIndex,] =  $this->getDataFields($dataSource);
    //     if (!$this->getDataFields($dataSource)) return [];
    //     $allRowFields = array_unique(array_merge($rowFields, $dataIndex));
    //     // dd($dataSource);
    //     foreach ($dataSource as $key => $values) {
    //         // dd($values);
    //         foreach ($allRowFields as $field) {
    //             // $attrName = str_replace('id', 'name', $field);
    //             if (isset($values[$field])) {
    //                 // Log::info($field);
    //                 $values[$field] = (object) [
    //                     'value' => $values[$field],
    //                     // 'cell_title' => $tooltip,
    //                 ];
    //             }
    //         }
    //         $dataSource[$key] = $values;
    //     }
    //     return $dataSource;
    // }

    private function mergeLines($data, $rowFields)
    {
        if ($rowFields) return $data;
        return [array_merge(...array_values($data))];
    }

    private function makeTopTitle($data, $rowFields, $columnFields)
    {
        if ($rowFields) return $data;
        if ($columnFields) {
            foreach ($data as $key => &$items) {
                $keyName =  '';
                foreach ($columnFields as $field) {
                    if (!$field) continue;
                    $keyName .= $items[$field] . '_';
                }
                $keyName = trim($keyName, '_');
                $items[$keyName] = end($items);
            }
        }
        // dd($data);
        return $data;
    }

    private function getFieldNeedToSum($propsColumnField)
    {
        $firstItem = reset($propsColumnField);
        if ($firstItem) {
            $fieldIndex = array_column($propsColumnField, 'fieldIndex');
            $fields = array_unique(array_column($propsColumnField, 'valueIndexField'));
            return ['fieldIndex' => $fieldIndex, 'valueIndexField' => $fields];
        }
        return [];
    }

    private function triggerFilters($topParams, $fieldOfFilters)
    {
        if (empty($fieldOfFilters)) return [];
        $dataFilters = [];
        foreach ($fieldOfFilters as $fieldFilter) {
            if (isset($topParams['many_' . $fieldFilter])) {
                $dataFilters[$fieldFilter] = (array)$topParams['many_' . $fieldFilter];
            } elseif (isset($topParams[$fieldFilter])) {
                $dataFilters[$fieldFilter] = (array)$topParams[$fieldFilter];
            }
        }
        return $dataFilters;
    }

    public function makeDataRenderer($linesData, $allDataFields, $topParams)
    {
        if (empty($linesData->toArray())) return [];
        
        [
            $rowFields,,
            $fieldOfFilters,
            $propsColumnField,,
            $dataAggregations,,,
            $valueIndexFields,
            $columnFields,
            $infoColumnFields,
            $tableIndex,,
        ] =  $allDataFields;

        if (is_object($linesData)) $linesData = array_map(fn ($item) => (array)$item, $linesData->toArray());
        // Step 1: reduce lines from Filters array
        $keysFilters = $this->triggerFilters($topParams, $fieldOfFilters);
        $dataReduce = PivotReport::reduceDataByFilterColumn($linesData, $keysFilters);
        // dd($dataReduce);

        // Step 2: group lines by Row_Fields array
        if (!count($rowFields)) {
            $processedData = PivotReport::groupBy($dataReduce, $columnFields);
        } else {
            $processedData = PivotReport::groupBy($dataReduce, $rowFields);
        }
        // dump($processedData);

        //Remove all array keys by looping through all elements
        $fieldsNeedToSum = $this->getFieldNeedToSum($propsColumnField);
        $processedData = array_values(array_map(fn ($item) => PivotReport::getLastArray($item, $fieldsNeedToSum), $processedData));
        // dd($processedData, $fieldsNeedToSum);
        // dump($processedData);


        // Step 3: transfer data from lines to columns by
        // Column_Fields and Value_Index_Fields array 
        $transferredData = PivotReport::transferData($processedData, $columnFields, $propsColumnField, $valueIndexFields);
        // dump($transferredData);

        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        $calculatedData = PivotReportDataFields::executeOperations($dataAggregations, $processedData, $rowFields);
        // dump($calculatedData);

        $dataIdsOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData, $rowFields, $columnFields);
        // dd($dataIdsOutput);

        $tables = $this->getDataFromTables($tableIndex);
        $dataOutput = $this->attachInfoToDataSource($tables, $dataIdsOutput, $allDataFields);
        // dd($dataOutput[0]);
        $dataOutput = $this->makeTopTitle($dataOutput, $rowFields, $columnFields);
        // dd($dataOutput[0]);

        $infoColumnFields = [];
        if (!$rowFields && $columnFields) {
            $groupByColumnFields = Report::groupArrayByKey($dataOutput, $columnFields[0]);
            foreach ($groupByColumnFields as $k1 => $values) {
                foreach ($values as $value) {
                    $lastItem = array_slice($value, count($value) - 1, count($value));
                    $infoColumnFields[$k1][] = $lastItem;
                }
            }
        }
        $dataOutput = $this->mergeLines($dataOutput, $rowFields);
        if (!$rowFields && $columnFields) {
            $dataOutput[0]["info_column_field"] = $infoColumnFields;
        }
        $dataOutput = $this->updateResultOfAggregations($columnFields, $dataAggregations, $dataOutput);
        // dump($dataOutput);

        return $dataOutput;
    }

    private function updateResultOfAggregations($columnFields, $dataAggregations, $dataOutput)
    {
        if (PivotReport::hasDuplicates($columnFields)) {
            $newColumnFields = PivotReport::markDuplicatesAndGroupKey($columnFields);
            $num = count(reset($newColumnFields));

            $dataIndex =  array_column($dataAggregations, 'type_operator', 'name');
            $field = array_slice($dataIndex, 0, 1);
            $keys = array_keys($field) ?? '';
            $value = array_values($field);
            $str = $value[0] . "_" . $keys[0];

            foreach ($dataOutput as &$values) {
                if (isset($values[$str])) {
                    $values[$str] = $values[$str] * $num;
                }
            }
        }
        return $dataOutput;
    }
}
