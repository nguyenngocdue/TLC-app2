<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use App\Http\Controllers\Reports\TraitDataModesReport;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;


class Hr_timesheet_line_010 extends Report_ParentController2

{
    use TraitDynamicColumnsTableReport;
    use TraitDataModesReport;
    protected $maxH = 50;
    protected $libPivotFilters;
    protected $typeView = 'report-pivot';
    protected $modeType = '';
    protected $tableTrueWidth = true;

    public function getDataSource($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource($modeParams);
        return $primaryData;
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
