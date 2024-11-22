<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_100 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $maxH = 50 * 16;
    protected $typeView = 'report-pivot';
    protected $modeType = 'datasource_hr_timesheet_line';
    protected $tableTrueWidth = true;
    protected $mode = '100';


    public function getDataSource($params)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($params);
        // $primaryData = array_slice($primaryData->toArray(), 0, 100);
        return collect($primaryData);
    }
}
