<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_030 extends Report_ParentReport2Controller

{
    use TraitForwardModeReport;

    protected $maxH = 50 * 16;
    protected $typeView = 'report-pivot';
    protected $modeType = 'hr_timesheet_line_employee_date';
    protected $tableTrueWidth = true;
    protected $mode = '030';


    public function getDataSource($params)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($params);
        return $primaryData;
    }
}
