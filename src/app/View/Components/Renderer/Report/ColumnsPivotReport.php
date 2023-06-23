<?php

namespace App\View\Components\Renderer\Report;


use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use Illuminate\View\Component;
use Illuminate\Support\Str;

trait  ColumnsPivotReport 
{
    use TraitLibPivotTableDataFields;
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

    protected function editRowHeaderColumnFields($fieldGroups)
    {
        $array = [];
        foreach (array_values($fieldGroups) as $fields) {
            foreach ($fields as  $field) {
                $thirdUnderscore = ReportPivot::findPosition($field, '_', 3);
                if ($thirdUnderscore) {
                    $date = Report::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/y');
                    if ($date) {
                        $array[$field] = str_replace('/', '<br/>', $date);
                    }
                }
            }
        };
        return $array;
    }

    private function sortDates($a)
    {
        $result = [];
        $lib = LibPivotTables::getFor($this->key);
        $b = $lib['column_fields'];
        $result = [];
        foreach ($b as $value) {
            foreach ($a as $item) {
                $thirdUnderscore = ReportPivot::findPosition($item, '_', 3);
                if (substr($item, $thirdUnderscore) === $value) {
                    $result[substr($item, 9)][] = $item;
                }
            }
            // $result[] = $group;
        }
        $otherItems = array_diff($a, array_merge(...array_values($result)));
        $result['other'] = $otherItems;
        return  $result;
    }


    private function makeColumnsOfColumnFields($dataOutput, $dataIndex)
    {
        $allColumns = [];
        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));

        $lastItemDataSource = key(array_slice($this->dataSource[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        $diffFields = array_diff($endArray, $dataIndex);
        $fields = $this->sortDates($diffFields);
        // dd($fields);
        $columnsOfColumnFields = [];
        array_walk(
            $fields,
            function ($items, $key) use (&$columnsOfColumnFields) {
                if ($key === 'other') {
                    foreach ($items as $value) {
                        if (str_contains($value, '_id_')) continue;
                        $columnsOfColumnFields[] = [
                            'title' => ucwords($value),
                            'dataIndex' => $value,
                            'align' => 'center',
                            'width' => 60,
                        ];
                    }
                } else {
                    foreach ($items as $value) {
                        $thirdUnderscore = ReportPivot::findPosition($value, '_', 3);
                        if (!$thirdUnderscore) continue;
                        $columnsOfColumnFields[] = [
                            'title' => ucwords(str_replace('_', ' ', substr($value, $thirdUnderscore, 50))),
                            'dataIndex' => $value,
                            'align' => 'center',
                            'colspan' => count($items),
                        ];
                    }
                }
            }
        );
        $tableDataHeader = $this->editRowHeaderColumnFields($fields);
        // dd($columnsOfColumnFields, $tableDataHeader);
        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private function makeColumnsOfAgg($dataAggregations)
    {
        $columnsOfAgg = [];
        foreach ($dataAggregations as $field => $fn) {
            $columnsOfAgg[] = [
                'dataIndex' => $fn . '_' . $field,
                'align' => 'right',
                'width' => 40,
            ];
        };
        return $columnsOfAgg;
    }

    public function makeColumnsRenderer($dataOutput)
    {
        [, $bidingRowFields,,,, $dataAggregations, $dataIndex,] =  $this->getDataFields();
        $columnsOfRowFields = $this->makeHeadColumn($bidingRowFields);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($dataOutput, $dataIndex);
        $columnsOfAgg = $this->makeColumnsOfAgg($dataAggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        return [$tableDataHeader, $tableColumns];
    }


}
