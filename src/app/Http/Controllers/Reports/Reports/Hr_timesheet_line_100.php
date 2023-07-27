<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController2;
use App\Http\Controllers\Reports\TraitDataModesReport;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;


class Hr_timesheet_line_100 extends Report_ParentController2

{
    use TraitDynamicColumnsTableReport;
    use TraitDataModesReport;
    protected $maxH = 50;
    protected $libPivotFilters;
    protected $typeView = 'report-pivot';
    protected $modeType = 'datasource_hr_timesheet_line';
    protected $tableTrueWidth = true;

    public function getDataSource($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_dataSource())->getDataSource($modeParams);
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
