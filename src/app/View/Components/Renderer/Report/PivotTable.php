<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use App\Utils\Support\ReportPivotDataFields;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;
class PivotTable extends Component
{

    use TraitLibPivotTableDataFields;
    public function __construct(
        private $key = '',
        private $dataSource =[],
    ) {}


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
        [$rowFieldsHasAttr, $bindingFields,,, $bidingColumnFields,,] =  $this->getDataFields();
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

    private function makeHeadColumn($bidingRowFields)
    {
        $columnsData = [];
        foreach ($bidingRowFields as $key => $value) {
            if (count($value) and is_array($value)) {
                $dataIndex = Str::singular($value['table_name']) . '_' . $value['attribute_name'];
                $title = ucwords(str_replace('_', ' ', substr($key, 0, strrpos($key, '_'))));
                $columnsData[] = [
                    'title' => $title,
                    'dataIndex' => $dataIndex,
                    'width' => 250,
                ];
            } else {
                $columnsData[] = [
                    'dataIndex' => $key,
                    'width' => 250,
                ];
            }
        }
        // dd($columnsData);
        return $columnsData;
    }

    private function sortDates($a)
    {
        $result = [];
        $lib = LibPivotTables::getFor($this->key);
        $b = $lib['column_fields'];
        $result = [];
        foreach ($b as $value) {
            $group = [];
            foreach ($a as $item) {
                if (substr($item, 11) === $value) {
                    $group[] = $item;
                }
            }
            $result[] = $group;
        }
        return array_merge(...array_values($result));
    }

    private function makeColumnsOfColumnFields($allColumns, $dataIndex)
    {
        $lastItemDataSource = key(array_slice($this->dataSource[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        $diffFields = array_diff($endArray, $dataIndex);
        $fields = $this->sortDates($diffFields) + $diffFields;
        $columnsOfColumnFields = array_map(function ($item) {
            return [
                'dataIndex' => $item,
                'align' => 'center',
                'width' => 40
            ];
        }, array_filter($fields, function ($item) {
            return !str_contains($item, '_id');
        }));
        return $columnsOfColumnFields;
    }


    private function makeColumnsRenderer($dataOutput)
    {
        [, $bidingRowFields,,,, $dataAggregations, $dataIndex,] =  $this->getDataFields();
        // dd($bidingRowFields, $rowFields);
        $columnsOfRowFields = $this->makeHeadColumn($bidingRowFields);
        $allColumns = [];
        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));
        $columnsOfColumnFields = $this->makeColumnsOfColumnFields($allColumns, $dataIndex);
        $columnsOfAgg = [];
        foreach ($dataAggregations as $filed => $fn) {
            $columnsOfAgg[] = [
                'dataIndex' => $fn . '_' . $filed,
                'align' => 'right',
                'width' => 40
            ];
        };
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        return $tableColumns;
    }

    private function createSortByData($sortByColumn){
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
        [,,,,,, $dataIndex, $sortBy] =  $this->getDataFields();
        $rules = $this->createSortByData($sortBy);
        // dd($rules);
        // dump($dataOutput, $dataIndex, $rules);
        usort($dataOutput, function ($item1, $item2) use ($rules) {
            foreach ($rules as $field => $sortOrder) {
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


    public function render()
    {
        $primaryData = $this->dataSource;
        // dd($primaryData);
        $dataOutput = $this->makeDataRenderer($primaryData);
        $tableColumns = $this->makeColumnsRenderer($dataOutput);
        
        $dataOutput = $this->sortLinesData($dataOutput);
        return view('components.renderer.report.pivot-table',[
            'tableDataSource' => $dataOutput,
            'tableColumns' => $tableColumns
        ]);
    }
}
