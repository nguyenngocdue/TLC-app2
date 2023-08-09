<?php

namespace App\View\Components\Reports\ModeParams;

use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;
use Illuminate\Support\Facades\DB;

class ParamMonth extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $sql = "SELECT DISTINCT(SUBSTR(otlineDate.otl_date,1, 7)) AS year_months
                                FROM (SELECT 	otline.ot_date AS otl_date
                                        FROM hr_overtime_request_lines otline
                                        WHERE 1 =1 
                                        AND otline.deleted_at IS NULL
                                        AND otline.ot_date IS NOT NULL
                                        GROUP BY otl_date) AS otlineDate
                                        ORDER BY year_months DESC";
        $sqlData = DB::select(DB::raw($sql));
        $dataSource = [];
        foreach ($sqlData as $item) $dataSource[] = ['id' => $item->year_months, 'name' => $item->year_months];
        return $dataSource;
    }
}
