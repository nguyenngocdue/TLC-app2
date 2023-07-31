<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Hr_timesheet_line_050 extends Report_ParentController2

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $libPivotFilters;
    protected $typeView = 'report-pivot';
    protected $modeType = 'datasource_hr_timesheet_line';
    protected $tableTrueWidth = true;
    protected $mode='050';


    public function getDataSource($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($modeParams);
        $primaryData = array_slice($primaryData->toArray(), 0, 100);
        return collect($primaryData);
    }


    public function getSqlStr($modeParams)
    {
      return "";
    }

    protected function getTableColumns($dataSource, $modeParams)
    {
        $dataColumn1 = [[]];
        return $dataColumn1;
    }

}
