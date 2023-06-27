<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use App\Utils\Support\ReportPivotDataFields;
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


    private function attachToDataSource($processedData, $calculatedData, $transferredData)
    {
        $dataOutput = [];
        foreach ($processedData as $k1 => $items) {
            foreach ($items as $k2 => $item) $dataOutput[] = $calculatedData[$k1][$k2] + reset($item) + $transferredData[$k1][$k2];
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


    private function makeDataRenderer($primaryData)
    {
        [$rowFieldsHasAttr,, $filters, $columnFields,, $dataAggregations,,,$valueIndexFields] =  $this->getDataFields();
        // dd($bidingRowFields);
        $valueFilters = array_combine($filters, [[1, 4], [7, 8]]);
        // Step 1: reduce lines from Filters array
        $linesData = $primaryData;
        $dataReduce = ReportPivot::reduceDataByFilterColumn($linesData, $valueFilters);
        // dd($valueFilters, $dataReduce);

        // Step 2: group lines by Row_Fields array
        $processedData = ReportPivot::groupBy($dataReduce, $rowFieldsHasAttr);
        //Remove all array keys by looping through all elements
        $processedData = array_values(array_map(fn ($item) => ReportPivot::getLastArray($item), $processedData));
        // dd($processedData);

        // Step 3: transfer data from lines to columns by
        // Column_Fields and Value_Index_Fields array 
        $transferredData = ReportPivot::transferData($processedData, $columnFields, $valueIndexFields);
        // dd($transferredData);

        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        $calculatedData = array_map(fn ($items) => ReportPivotDataFields::executeOperations($dataAggregations, $items), $processedData);
        // dd($calculatedData);
        $dataIdsOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData);
        // dd($processedData, $calculatedData, $dataIdsOutput);

        $tables = $this->getDataFromTables();
        $dataOutput = $this->attachInfoToDataSource($tables, $dataIdsOutput);
        // dd($dataIdsOutput[0], $dataOutput[0]);
        return $dataOutput;
    }

    private function attachInfoToDataSource($tables, $processedData)
    {

        [$rowFieldsHasAttr, $bindingFields,,, $bidingColumnFields,, $dataIndex,] =  $this->getDataFields();
        // dump($processedData, $bindingFields);
        foreach ($processedData as &$items) {
            foreach ($items as $key => $id) {
                if (in_array($key, $rowFieldsHasAttr)) {
                    try {
                        $infoAttr = $bindingFields[$key];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        $fieldName = $key . '_' .$tableName . '_' . $attributeName;
                        $items[$fieldName] = $tables[$tableName][$id]->$attributeName;
                    } catch (Exception $e) {
                        $items[$key] = $id;
                        // dump($e->getMessage());
                    }
                } else {
                    $lastUnderscoreIndex = strrpos($key, '_id_');
                    $id = substr($key, $lastUnderscoreIndex + 4, 2);
                    if (is_numeric($id)) {
                        // dump($key, $id);
                        $attr = substr($key, 0, $lastUnderscoreIndex + 3);
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
            $strKey = trim(str_replace(['(','.'],'_', $key), ')');
            $order = isset($ex[1]) ? strtolower($ex[1]) : 'asc';
            $orders[$strKey] = $order;
        }, $sortByColumn);
        // dd($orders);
        return $orders;
    }

    private function sortLinesData($dataOutput)
    {
        [,,,,,,, $sortBy] =  $this->getDataFields();

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
        // dd($dataOutput);
        return collect($dataOutput);
    }

    protected function changeValueData($dataSource)
    {
        $dataSource = array_slice($dataSource->toArray(), 0, 10000000);
        [$rowFields,,,,,, $dataIndex,] =  $this->getDataFields();
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

    public function render()
    {
        $primaryData = $this->dataSource;
        $dataOutput = $this->makeDataRenderer($primaryData);
        // dump($dataOutput);
        [$tableDataHeader, $tableColumns] = $this->makeColumnsRenderer($dataOutput);
        $dataOutput = $this->sortLinesData($dataOutput);
        $dataOutput = $this->changeValueData($dataOutput);
        return view('components.renderer.report.pivot-table', [
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
        ]);
    }
}
