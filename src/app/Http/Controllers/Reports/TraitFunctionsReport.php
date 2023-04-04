<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

trait TraitFunctionsReport
{
    function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }

    protected function makeColumns($dataSource, $modeParams)
    {
        $columns = $this->getTableColumns($dataSource, $modeParams);
        $columnKeys = array_column($columns, 'dataIndex');
        $columnNames =  array_map(function ($item) {
            if (!isset($item['title'])) return Report::makeTitle($item['dataIndex']);
            return $item['title'];
        }, $columns);
        return [$columnKeys, $columnNames];
    }
    protected function makeRowsFollowColumns($dataSource, $columnKeys)
    {
        $rows = [];
        $columnKeys = array_combine($columnKeys, $columnKeys);
        foreach ($dataSource as $key => $value) {
            $rows[] = array_intersect_key((array)$value, $columnKeys);
        }
        return $rows;
    }
}
