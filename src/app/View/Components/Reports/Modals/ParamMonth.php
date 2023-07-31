<?php

namespace App\View\Components\Reports\Modals;

use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamMonth extends ParentParamReports
{
    protected function getDataSource()
    {
        $sql = "SELECT DISTINCT(SUBSTR(otline.ot_date, 1, 7)) AS year_months
        FROM hr_overtime_request_lines otline
        WHERE otline.deleted_at IS NULL
        ORDER BY year_months DESC";
        $sqlData = DB::select(DB::raw($sql));
        $dataSource = [];
        foreach ($sqlData as $item) $dataSource[] = ['id' => $item->year_months, 'name' => $item->year_months];
        return $dataSource;
    }
}
