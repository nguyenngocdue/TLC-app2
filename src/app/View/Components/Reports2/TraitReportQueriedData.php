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
        $sql = '';
        if ($sqlString) {
            $sql = $this->getSql($sqlString, $params);
            $sqlData = DB::select($sql);
            $queriedData = collect($sqlData);
            $transformedFields = [];
            if ($block->is_transformed_data) {
                [$queriedData, $transformedFields]  = $this->getTransformedData($queriedData, $block->transformed_data_string);
            } else {
                $queriedData = $this->getNormalData($queriedData, $block);
            }
            return [$queriedData, $transformedFields, $sql];
        }
        return [collect(), [], $sql];
    }
}
