<?php

namespace App\View\Components\Reports2;

use App\Http\Controllers\Reports\TraitCreateSQLReport2;
use Illuminate\Support\Facades\DB;

trait TraitReportQueriedData
{
    use TraitReportTransformedData;
    use TraitCreateSQLReport2;

    public function getDataSQLString($block, $params)
    {
        $sqlString = $block->sql_string;
        if ($sqlString) {
            $sql = $this->getSql($sqlString, $params);
            $sqlData = DB::select($sql);
            $queriedData = collect($sqlData);
            $fieldTransformation = [];
            if ($block->is_transformed_data){
                [$queriedData , $fieldTransformation]  = $this->transformData($queriedData, $block->transformed_data_string);
            }
            return [ $queriedData, $fieldTransformation ];
        }
        return [collect(), []];
    }
}
