<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\CheckFieldPivotInDatabase;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use App\Utils\Support\PivotReportDataFields;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class PivotTable extends Component
{

    use TraitLibPivotTableDataFields;
    use ColumnsPivotReport;
    public function __construct(
        private $key = '',
        private $dataSource = [],
    ) {
    }


    private function attachToDataSource($processedData, $calculatedData, $transferredData, $rowFields)
    {
        $dataOutput = [];
        foreach ($processedData as $k1 => $items) {
            foreach ($items as $k2 => $item) {
                $dt = isset($calculatedData[$k1][$k2]) ? $calculatedData[$k1][$k2] : [];
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

    private function getDataFromTables()
    {
        $lib = LibPivotTables::getFor($this->key);
        $tableNames = $lib['lookup_tables'] ?? [];
        $dataTables = array_merge(...array_map(function ($name) {
            try {
                $array = DB::table($name)->select('id', 'name', 'description')->get()->toArray();
                $array = array_combine(array_column($array, 'id'), $array);
                return [$name => $array];
            } catch (\Exception $e) {
                $array = DB::table($name)->select('id', 'name')->get()->toArray();
                $array = array_combine(array_column($array, 'id'), $array);
                return [$name => $array];
            }
        }, $tableNames));
        // dump($tableNames);
        return $dataTables;
    }

    private function attachInfoToDataSource($tables, $processedData)
    {

        [$rowFieldsHasAttr, $bindingFields,,, $bidingColumnFields,, $dataIndex,] =  $this->getDataFields();
        foreach ($processedData as &$items) {
            foreach ($items as $key => $id) {
                if (in_array($key, $rowFieldsHasAttr)) {
                    try {
                        $infoAttr = $bindingFields[$key];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        $fieldName = $key . '_' . $tableName . '_' . $attributeName;
                        $items[$fieldName] = $tables[$tableName][$id]->$attributeName;
                    } catch (Exception $e) {
                        $items[$key] = $id;
                        // dump($e->getMessage());
                    }
                } else {
                    if (!str_contains($key, '_id') ) continue;
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
                    if (is_numeric(trim($id,'_')) && $id) {
                        $attr = substr($key, 0, $indexString + 3);
                        $infoAttr = $bidingColumnFields[$attr];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        $name = $tables[$tableName][$id]->$attributeName;
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

    private function sortLinesData($dataOutput)
    {
        // dd($dataOutput);
        [,,,,,,, $sortBy] =  $this->getDataFields();
        if (!$this->getDataFields()) return collect($dataOutput);

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

    protected function changeValueData($dataSource)
    {
        $dataSource = array_slice($dataSource->toArray(), 0, 10000000);
        [$rowFields,,,,,, $dataIndex,] =  $this->getDataFields();
        if (!$this->getDataFields()) return [];
        $allRowFields = array_unique(array_merge($rowFields, $dataIndex));
        // dd($dataSource);
        foreach ($dataSource as $key => $values) {
            // dd($values);
            foreach ($allRowFields as $field) {
                // $attrName = str_replace('id', 'name', $field);
                if (isset($values[$field])) {
                    Log::info($field);
                    $values[$field] = (object) [
                        'value' => $values[$field],
                        // 'cell_title' => $tooltip,
                    ];
                }
            }
            $dataSource[$key] = $values;
        }
        return $dataSource;
    }

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

    private function makeDataRenderer($primaryData)
    {
        [$rowFields,, $filters, $propsColumnField,, $dataAggregations,,, $valueIndexFields, $columnFields, $infoColumnFields] =  $this->getDataFields();
        $valueFilters = count($filters)  ? array_combine($filters, [[1, 4], [7, 8]]) : [];
        // Step 1: reduce lines from Filters array
        $linesData = $primaryData;
        // dd($linesData);
        $dataReduce = PivotReport::reduceDataByFilterColumn($linesData, $valueFilters);
        // dd($valueFilters, $dataReduce);


        // dump($columnFields);
        // Step 2: group lines by Row_Fields array
        if (!count($rowFields)) {
            $processedData = PivotReport::groupBy($dataReduce, $columnFields);
        } else {
            $processedData = PivotReport::groupBy($dataReduce, $rowFields);
        }
        // dump($processedData, $propsColumnField);

        //Remove all array keys by looping through all elements
        $fieldsNeedToSum = $this->getFieldNeedToSum($propsColumnField);
        $processedData = array_values(array_map(fn ($item) => PivotReport::getLastArray($item, $fieldsNeedToSum), $processedData));
        // dd($processedData, $propsColumnField);

        // Step 3: transfer data from lines to columns by
        // Column_Fields and Value_Index_Fields array 
        $transferredData = PivotReport::transferData($processedData, $columnFields, $propsColumnField, $valueIndexFields);
        // dd($transferredData);

        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        $calculatedData = PivotReportDataFields::executeOperations($dataAggregations, $transferredData, $processedData, $rowFields, $columnFields);
        // dump( $calculatedData);

        $dataIdsOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData, $rowFields, $columnFields);
        // dd($dataIdsOutput);

        $tables = $this->getDataFromTables();
        $dataOutput = $this->attachInfoToDataSource($tables, $dataIdsOutput);
        // dd($dataOutput);

        $dataOutput = $this->makeTopTitle($dataOutput, $rowFields, $columnFields);
        // dd($dataOutput);

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
        // dd($infoColumnFields, $dataOutput);
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

            $field = array_slice($dataAggregations, 0, 1);
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

    public function render()
    {
        $primaryData = $this->dataSource;
        if (!$this->getDataFields()) return false;
        $dataOutput = $this->makeDataRenderer($primaryData);
        [$tableDataHeader, $tableColumns] = $this->makeColumnsRenderer($dataOutput);
        $dataOutput = $this->sortLinesData($dataOutput);
        $dataOutput = $this->changeValueData($dataOutput);

        // dump($dataOutput);
        return view('components.renderer.report.pivot-table', [
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
        ]);
    }
}
