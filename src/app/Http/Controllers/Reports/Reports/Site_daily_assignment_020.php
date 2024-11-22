<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Site_daily_assignment_020 extends Report_ParentReport2Controller
{
    use TraitForwardModeReport;
    protected $maxH = 50 * 16;
    protected $typeView = 'report-pivot';
    protected $modeType = 'site_daily_assignment_task_user';
    protected $tableTrueWidth = false;
    protected $mode = '020';

    public function getDataSource($params)
    {
        $primaryData = (new Site_daily_assignment_dataSource2())->getDataSource($params);
        // dump($primaryData);
        return $primaryData;
    }
}
