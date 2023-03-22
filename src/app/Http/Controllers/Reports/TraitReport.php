<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitReport
{
    function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    function createTableColumns($dataSource, $strFromField = '', $strToFields = '', $editFields = [], $unsetFields = [], $align = 'center', $width = '100')
    {
        if (empty($dataSource->items())) return [[]];
        $array = (array)array_slice($dataSource->items(), 0, 1)[0];
        $fields = [];
        if ($strFromField || $strToFields) {
            $fromField =  ($x = array_search($strFromField, array_keys($array))) ? $x : 0;
            $toField = ($x = array_search($strToFields, array_keys($array))) ? $x : count($array);
            $fields = array_keys(array_slice($array, $fromField, $toField - $fromField + 1));
        } else {
            // dd($strFromField);
            $fields = array_keys($array);
        }
        $fields =  array_diff(array_values($fields), $unsetFields);
        sort($fields);
        // dd($fields);
        $locateEditFields = array_column($editFields, 'dataIndex');
        $dataColumn = [];
        foreach ($fields as $key => $value) {
            if (in_array($value, $locateEditFields)) {
                $indexEdit = array_search($value, $locateEditFields);
                $dataColumn[] =  $editFields[$indexEdit];
            } else {
                $dataColumn[] = ['dataIndex' => $value, 'align' => $align, 'width' => $width];
            }
        }
        return $dataColumn;
    }
}
