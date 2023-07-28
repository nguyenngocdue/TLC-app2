<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_020 extends Report_ParentController2

{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'hr_timesheet_line_employee_project';
    protected $tableTrueWidth = true;

    public function getDataSource($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($modeParams);
        return $primaryData;
    }
}
