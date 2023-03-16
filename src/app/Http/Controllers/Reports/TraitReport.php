<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitReport
{
    function isNullModeParams($modeParams)
    {
        return count(array_filter($modeParams, fn ($value) => !is_null($value))) <= 0;
    }

    function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    function createTableColumns($dataSource, $strFromField, $strToFields, $editFields = [], $unsetFields = [],)
    {
        $array = (array)array_slice($dataSource->items(), 0, 1)[0];
        $fromField = array_search($strFromField, array_keys($array));
        $toField = array_search($strToFields, array_keys($array));
        $fields = array_keys(array_slice($array, $fromField, $toField - $fromField + 1));
        foreach ($unsetFields as $value) {
            $index = array_search($value, $fields);
            unset($fields[$index]);
        }
        $locateEditFields = array_column($editFields, 'dataIndex');
        $dataColumn = [];
        foreach ($fields as $value) {
            if (in_array($value, $locateEditFields)) {
                $indexEdit = array_search($value, $locateEditFields);
                $dataColumn[] =  $editFields[$indexEdit];
            }
            $dataColumn[] = ['dataIndex' => $value, 'align' => 'center'];
        }
        return $dataColumn;
    }
}
