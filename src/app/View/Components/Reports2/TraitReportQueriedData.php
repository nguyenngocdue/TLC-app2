<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use Illuminate\Support\Facades\DB;

trait TraitReportQueriedData
{
    use TraitReportTransformedData;
    use TraitCreateSQLReport2;

    // public function getDataSQLString($block, $params)
    // {
    //     $sqlString = $block->sql_string;
    //     $formattedSqlString = '';
    //     if ($sqlString) {
    //         $sql = $this->getSql($sqlString, $params);
    //         $formattedSqlString = $sql;
    //         $sqlData = DB::select($sql);
    //         $queriedData = collect($sqlData);
    //         return $queriedData;
    //     }
    //     return [collect(), $formattedSqlString];
    // }


    public function getDataSQLString($sqlString)
    {
        if ($sqlString) {
            $sqlData = DB::select($sqlString);
            $queriedData = collect($sqlData);
            return $queriedData;
        }
        return collect();
    }
}
