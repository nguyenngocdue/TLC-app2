<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_100 extends Report_ParentController2

{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'datasource_hr_timesheet_line';
    protected $tableTrueWidth = true;

    public function getDataSource($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($modeParams);
        return $primaryData;
    }

}
