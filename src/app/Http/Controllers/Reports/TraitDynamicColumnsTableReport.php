<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitDynamicColumnsTableReport
{
    function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    function createTableColumns(
        $dataSource,
        $strFromField = '',
        $align = 'right',
        $width = 100,
        $sort = True,
    ) {
        // dd($dataSource);
        if (empty($dataSource->items())) return [[]];
        $array = (array)array_slice($dataSource->items(), 0, 1)[0];

        $datColumns = [];
        if ($strFromField) {
            $fromField =  ($x = array_search($strFromField, array_keys($array))) ? $x + 1 : 0;
            $datColumns = array_keys(array_slice($array, $fromField, count($array) - $fromField + 1));
        } else {
            $datColumns = array_keys($array);
        }
        $sort ? sort($datColumns) : $datColumns;
        array_walk($datColumns, function ($colName, $key) use (&$datColumns, $align, $width) {
            $datColumns[$key] = [
                'dataIndex' => $colName,
                'align' => $align,
                'width' => $width
            ];
        });
        return $datColumns;
    }
}
