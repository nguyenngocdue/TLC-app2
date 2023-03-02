<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Support\Facades\DB;

trait TraitReport
{
    function isNullModeParams($modeParams)
    {
        return count(array_filter($modeParams, fn ($value) => !is_null($value))) <= 0;
    }

    protected function getDataSourceFromSqlStr($sql)
    {
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }
}
