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
            if (!$key) continue;
            if (count($value) and is_array($value)) {
                $dataIndex = $value['table_name'] . '_' . $value['attribute_name'];
                $title = ucwords(str_replace('_', ' ', substr($key, 0, strrpos($key, '_'))));
                $columnsData[] = [
                    'title' => $title,
                    'dataIndex' => $key . '_' . $dataIndex,
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

    protected function editRowHeaderColumnFields($fieldGroups, $valueIndexFields)
    {
        $array = [];
        // dump($fieldGroups);
        foreach ($fieldGroups as $key => $fields) {
            if ($key !== 'other') {
                foreach ($fields as  $field) {
                    $thirdUnderscore = ReportPivot::findPosition($field, '_', 3);
                    if ($thirdUnderscore) {
                        $date = Report::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/y');
                        if ($date) $array[$field] = str_replace('/', '<br/>', $date);
                    }
                }
            } else {
                foreach ($fields as $field) {
                    $checkFiled =  ReportPivot::isStringInItemsOfArray($valueIndexFields, $field);
                    if ($checkFiled) {
                        $title = strtoupper(substr($field, strrpos($field, '_')+1, 50));
                        $array[$field] = $title;
                    }
                }
            
            }
        };
        // dd($array);
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
                    $result[substr($item, $thirdUnderscore)][] = $item;
                }
            }
            // $result[] = $group;
        }
        $otherItems = array_diff($a, array_merge(...array_values($result)));
        $result['other'] = $otherItems;
        return  $result;
    }



    private function makeColumnsOfColumnFields($dataOutput, $dataIndex, $valueIndexFields)
    {
        $allColumns = [];
        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));

        // dd($allColumns);

        $lastItemDataSource = key(array_slice($this->dataSource[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        $diffFields = array_diff($endArray, $dataIndex);
        $fields = $this->sortDates($diffFields);

        $topTitleColumns = array_merge(...array_column($dataOutput, 'top_title_column'));
        $columnsOfColumnFields = [];
        array_walk(
            $fields,
            function ($items, $key) use (&$columnsOfColumnFields, $topTitleColumns, $valueIndexFields) {
                if ($key === 'other') {
                    foreach ($items as $value) {
                        $checkFiled =  ReportPivot::isStringInItemsOfArray($valueIndexFields, $value);
                        if ($checkFiled) {
                            $columnsOfColumnFields[] = [
                                'title' => $topTitleColumns[$value],
                                'dataIndex' => $value,
                                'align' => 'center',
                                'width' => 50,
                                'colspan' => 3,
                            ];
                        };
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

        $tableDataHeader = $this->editRowHeaderColumnFields($fields, $valueIndexFields);
        // dump($tableDataHeader, $columnsOfColumnFields);
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
        [, $bidingRowFields,,,, $dataAggregations, $dataIndex,,$valueIndexFields] =  $this->getDataFields();
        $columnsOfRowFields = $this->makeHeadColumn($bidingRowFields);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($dataOutput, $dataIndex, $valueIndexFields);
        $columnsOfAgg = $this->makeColumnsOfAgg($dataAggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        return [$tableDataHeader, $tableColumns];
    }
}
