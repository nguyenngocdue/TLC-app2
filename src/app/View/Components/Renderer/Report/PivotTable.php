<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use App\Utils\Support\ReportPivotDataFields;
use DateTime;
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
        [$rowFieldsHasAttr,, $filters, $columnFields,, $dataAggregations,,] =  $this->getDataFields();
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
        $transferredData = ReportPivot::transferData($processedData, $columnFields);
        // dd($transferredData);

        //Step 4: Calculate data from Data Fields columns
        //The aggregated data are at the end of the items
        $calculatedData = array_map(fn ($items) => ReportPivotDataFields::executeOperations($dataAggregations, $items), $processedData);

        $dataOutput = $this->attachToDataSource($processedData, $calculatedData, $transferredData);
        // dd($dataOutput);

        $tables = $this->getDataFromTables();
        $dataOutput = $this->attachInfoToDataSource($tables, $dataOutput);
        // dd($dataOutput, $tables);
        return $dataOutput;
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
                        $fieldName = Str::singular($tableName) . '_' . $attributeName;
                        $items[$fieldName] = $tables[$tableName][$id]->$attributeName;
                    } catch (Exception $e) {
                        // dump($e->getMessage());
                    }
                } else {
                    $lastUnderscoreIndex = strrpos($key, '_');
                    $id = substr($key, $lastUnderscoreIndex + 1);
                    if (is_numeric($id)) {
                        $attr = substr($key, 0, $lastUnderscoreIndex);
                        $infoAttr = $bidingColumnFields[$attr];
                        $tableName = $infoAttr['table_name'];
                        $attributeName = $infoAttr['attribute_name'];
                        $n = $tables[$tableName][$id]->$attributeName;
                        $items[$n] = $items[$key];
                    }
                }
            }
        }
        return $processedData;
    }

    private function sortByData($sortByColumn)
    {
        $rules = [];
        array_map(function ($item) use (&$rules) {
            [$posBracket, $posDot]  = [strpos($item, '('), strpos($item, '.',)];
            $field = Str::singular(substr($item, 0, $posDot)) . '_' . substr($item, $posDot + 1, $posBracket - $posDot - 1);
            $rule = substr($item, $posBracket + 1, strlen($item) - $posBracket - 2);
            // dump($field, $rule);
            if ($posBracket && $posDot) return $rules[$field] = $rule;
            if (!$posBracket && $posDot) {
                $field = Str::singular(substr($item, 0, $posDot)) . '_' . substr($item, $posDot + 1, strlen($item));
                $rules[$field] = 'asc';
            }
            if (!$posDot && $posBracket) {
                $field = substr($item, 0, $posBracket);
                return $rules[$field] = $rule;
            }
            if (!$posDot && !$posBracket) {
                return $rules[$item] = 'asc';
            }
        }, $sortByColumn);
        return $rules;
    }

    private function sortLinesData($dataOutput)
    {
        [,,,,,,, $sortBy] =  $this->getDataFields();
        $sortOrders = $this->sortByData($sortBy);
        usort($dataOutput, function ($item1, $item2) use ($sortOrders) {
            foreach ($sortOrders as $field => $sortOrder) {
                if (!array_key_exists($field, $item1) || !array_key_exists($field, $item2)) {
                    continue;
                }
                $comparison =  $item1[$field] <=> $item2[$field];
                if (strtolower($sortOrder) === 'desc') {
                    return $comparison *= -1;
                }
                return $comparison;
            }
            return 0;
        });
        return collect($dataOutput);
    }

    protected function changeValueData($dataSource)
    {
        $dataSource = array_slice($dataSource->toArray(), 0, 10000000);
        [$rowFields,,,,,, $dataIndex,] =  $this->getDataFields();
        $allRowFields = array_unique(array_merge($rowFields, $dataIndex));
        // dd($allRowFields);
        foreach ($dataSource as $key => $values) {
            foreach ($allRowFields as $field) {
                $attrName = str_replace('id', 'name', $field);
                if (isset($values[$attrName])) {
                    Log::info($attrName);
                    $values[$field] = (object) [
                        'value' => $values[$attrName],
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
        [$tableDataHeader, $tableColumns] = $this->makeColumnsRenderer($dataOutput);
        $dataOutput = $this->sortLinesData($dataOutput);
        $dataOutput = $this->changeValueData($dataOutput);
        // dd($dataOutput);
        return view('components.renderer.report.pivot-table', [
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
        ]);
    }
}
