<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Site_daily_assignment_010 extends Report_ParentReport2Controller
{
    use TraitForwardModeReport;
    protected $maxH = 50 * 16;
    protected $typeView = 'report-pivot';
    protected $modeType = 'site_daily_assignment_site_team_date';
    protected $tableTrueWidth = false;
    protected $mode = '010';

    public function getDataSource($params)
    {
        $primaryData = (new Site_daily_assignment_dataSource1())->getDataSource($params);
        return $primaryData;
    }
}
