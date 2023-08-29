<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use Exception;
use Illuminate\Support\Facades\DB;

class PivotTableController extends Controller
{

    use TraitLibPivotTableDataFields2;
    use PivotReportColumn2;
    public function __construct(
        private $modeType = '',
        private $dataSource = [],
        private $itemsSelected = [],
    ) {
    }


    private function attachToDataSource($processedData, $calculatedData, $transferredData, $keysOfRowFields)
    {
        $dataOutput = [];
        foreach ($processedData as $k1 => $items) {
            foreach ($items as $k2 => $item) {
                $dt = isset($calculatedData[$k1][$k2]) ? $calculatedData[$k1][$k2] : [];
                // $dt2 = isset($transferredData[$k1][$k2]) ?$transferredData[$k1][$k2] : [];
                $dataOutput[] = array_merge($dt, reset($item), $transferredData[$k1][$k2]);
            };
        }
        if (!$keysOfRowFields && $calculatedData) {
            $data1 = array_map(function ($item) use ($calculatedData) {
                return array_merge($calculatedData[0], $item);
            }, $dataOutput);
            return $data1;
        }
        return $dataOutput;
    }

    private function getTablesNamesFromLibs($libs) {
        $colData = array_merge($libs['row_fields'], $libs['column_fields']);
        $columns = array_column($colData, 'column');
        $tableName = [];
        foreach($columns as $col) {
            $str = substr($col, 0, strpos($col, '.'));
            $tableName[] = $str; 
        }
        return $tableName;
    }

    private function attachInfoToDataSource($tables, $processedData, $columnFields, $rowFields)
    {
        $keysOfRowFields = array_keys($rowFields);
        // dd($processedData);

        foreach ($processedData as &$items) {
            foreach ($items as $key => $id) {
                if (in_array($key, $keysOfRowFields)) {
                    try {
                        $infoAttr = $rowFields[$key]->column;
                        $tableName = substr($infoAttr, 0, strpos($infoAttr, '.'));
                        $attributeName = substr($infoAttr,strpos($infoAttr, '.')+1);
                        $fieldName = $key . '_' . $tableName . '_' . $attributeName;
                        $items[$fieldName] = $tables[$tableName][$id]->$attributeName;
                    } catch (Exception $e) {
                        $items[$key] = $id;
                        // dump($e->getMessage());
                    }
                } else {
                    if (!str_contains($key, '_id')) continue;
                    $indexString = strrpos($key, '_id_') || strrpos($key, '_id');
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
                        $infoAttr = $columnFields[$attr]->column;
                        $tableName = substr($infoAttr, 0, strpos($infoAttr, '.'));
                        $attributeName = substr($infoAttr,strpos($infoAttr, '.')+1);
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
        return $processedData;
    }

    private function makeKeysOrderSort($sortByFields)
    {
        $orders = [];
        // dd($sortByFields);
        foreach($sortByFields as $key => $values) {
            $str = str_replace('.', '_', $values->column);
            $orders[$key.'_'.$str] = $values->order ?? 'ASC';
        }
        return $orders;
    }

    public function sortLinesData($dataOutput, $libs)
    {
        $sortByFields = $libs['sort_by'];
        $sortOrders = $this->makeKeysOrderSort($sortByFields);
        uasort($dataOutput, function ($item1, $item2) use ($sortOrders) {
            foreach ($sortOrders as $field => $sortOrder) {
                if (!$field) continue;
                try {
                    $comparison = $item1[$field] <=> $item2[$field];
                    if ($comparison) {
                        return ($sortOrder === 'asc' || $sortOrder === 'ASC') ? $comparison : -$comparison;
                    }
                } catch (\Exception $e) {
                    // dd($e->getMessage() . ' in "Row_Fields" column');
                }
            }
            return 0;
        });
        return collect($dataOutput);
    }


    private function mergeLines($data, $keysOfRowFields)
    {
        if ($keysOfRowFields) return $data;
        return [array_merge(...array_values($data))];
    }

    private function makeTopTitle($data, $keysOfRowFields, $keysOfColumnFields)
    {
        if ($keysOfRowFields) return $data;
        if ($keysOfColumnFields) {
            foreach ($data as $key => &$items) {
                $keyName =  '';
                foreach ($keysOfColumnFields as $field) {
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

    private function getFieldNeedToSum($columnFields)
    {
        // dump($columnFields);
        if ($columnFields) {
            $fieldIndex = array_keys($columnFields);
            $fields = array_column($columnFields, 'value_index');
            return ['field_indexes' => $fieldIndex, 'value_field_indexes' => $fields];
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

    public function makeDataRenderer($linesData, $libs, $topParams)
    {
        $keysOfFilters = array_keys($libs['filters']);
        $keysOfColumnFields = array_keys($libs['column_fields']);
        $keysOfRowFields = array_keys($libs['row_fields']);
        $columnFields = $libs['column_fields'];
        $rowFields = $libs['row_fields'];
        $aggregations = $libs['data_fields'];
        $tableName = $this->getTablesNamesFromLibs($libs);

        if (is_object($linesData)) $linesData = array_map(fn ($item) => (array)$item, $linesData->toArray());
        if (empty($linesData)) return [];
        // Step 1: reduce lines from Filters array
        $keysFilters = $this->triggerFilters($topParams, $keysOfFilters);
        $dataReduce = PivotReport::reduceDataByFilterColumn($linesData, $keysFilters);
        // dd($rowFields);

        // Step 2: group lines by Row_Fields array
        if (!count($keysOfRowFields)) {
            $processedData = PivotReport::groupBy($dataReduce, $keysOfColumnFields);
        } else {
            $processedData = PivotReport::groupBy($dataReduce, $keysOfRowFields);
        }

        //Remove all array keys by looping through all elements
        // $fieldsNeedToSum = $this->getFieldNeedToSum($columnFields);
        
        // $processedData = array_values(array_map(fn ($item) => PivotReport::getLastArray($item, $fieldsNeedToSum), $processedData));
        $processedData = array_values(array_map(fn ($item) => PivotReport::getLastArray($item, $columnFields), $processedData));
        // dd($processedData);

        // Step 3: transfer data from lines to columns by
        // Column_Fields and Value_Index_Fields array 
        $transferredData = PivotReport::transferData2($processedData, $columnFields);
        // dd($processedData, $transferredData);

        
        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        // dd($columnFields);
        $calculatedData = PivotReportDataFields2::executeOperations($aggregations, $transferredData, $keysOfRowFields);

        $dataIdsOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData, $keysOfRowFields, $keysOfColumnFields);
        // dd($dataIdsOutput);

        $tables = $this->getDataFromTables($tableName);
        $dataOutput = $this->attachInfoToDataSource($tables, $dataIdsOutput, $columnFields, $rowFields);
        // dd($dataOutput);
        $dataOutput = $this->makeTopTitle($dataOutput, $keysOfRowFields, $keysOfColumnFields);
        
        $infoColumnFields = [];
        if (!$keysOfRowFields && $keysOfColumnFields) {
            $groupByColumnFields = Report::groupArrayByKey($dataOutput, $keysOfColumnFields[0]);
            foreach ($groupByColumnFields as $k1 => $values) {
                foreach ($values as $value) {
                    $lastItem = array_slice($value, count($value) - 1, count($value));
                    $infoColumnFields[$k1][] = $lastItem;
                }
            }
        }
        $dataOutput = $this->mergeLines($dataOutput, $keysOfRowFields);
        if (!$keysOfRowFields && $keysOfColumnFields) {
            $dataOutput[0]["info_column_field"] = $infoColumnFields;
        }
        
        
        $dataOutput = $this->updateResultOfAggregations($keysOfColumnFields, $aggregations, $dataOutput);
        return $dataOutput;
    }

    private function updateResultOfAggregations($keysOfColumnFields, $aggregations, $dataOutput)
    {
        if (PivotReport::hasDuplicates($keysOfColumnFields)) {
            $newColumnFields = PivotReport::markDuplicatesAndGroupKey($keysOfColumnFields);
            $num = count(reset($newColumnFields));
            // dd($aggregations);
            $dataIndex =  array_column($aggregations, 'type_operator', 'name');
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
