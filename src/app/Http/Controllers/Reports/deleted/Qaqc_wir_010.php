<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_dataSource;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\PivotReport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_010 extends Report_ParentController2

{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'project_summary_prod_sub_project_prod_routing';
    protected $tableTrueWidth = false;
    protected $mode = '010';

    public function getSqlStr($params)
    {
        $month = isset($params['month']) ? $params['month'] : implode('-', array_values(PivotReport::getCurrentYearAndMonth()));
        $sql = "SELECT 
                    SUBSTR(whrd1s.month,1,7) AS month
                    ,whrd1s.project_id AS project_id
                    ,whrd1s.sub_project_id AS sub_project_id
                    ,whrd1s.prod_routing_id AS prod_routing_id
                    ,whrd1s.quantity AS quantity
                    ,whrd1s.progress AS progress
                    FROM wh_report_data_1s whrd1s
                    WHERE 1 = 1
                    AND SUBSTR(whrd1s.month, 1, 7) = '$month'";
        return $sql;
    }
}
