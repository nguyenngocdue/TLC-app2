<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamESGMetricTypeId extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT
                        esgmt.id AS id,
                        esgmt.name AS name,
                        esgmt.description AS description 
                        FROM esg_metric_types esgmt
                        WHERE 1 = 1
                        ORDER BY esgmt.name";
        $result = DB::select($sql);
        return $result;
    }
}
