<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController2;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_010 extends Report_ParentReportController2
{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'hr_timesheet_line_project_date';
    protected $tableTrueWidth = true;
    protected $mode='010';

    public function getDataSource($params)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($params);
        return $primaryData;
    }
}
