<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Site_daily_assignment_020 extends Report_ParentReport2Controller
{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'site_daily_assignment_team_user_by_date';
    protected $tableTrueWidth = false;
    protected $mode='020';

    public function getDataSource($params)
    {
        $primaryData = (new Site_daily_assignment_line())->getDataSource($params);
        return $primaryData;
    }
}
