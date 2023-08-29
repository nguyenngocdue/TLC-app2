<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;

class Site_daily_assignment_110 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'datasource_site_daily_assignment';
    protected $tableTrueWidth = true;
    protected $mode='110';


    public function getDataSource($params)
    {
        $primaryData = (new Site_daily_assignment_line())->getDataSource($params);
        // $primaryData = array_slice($primaryData->toArray(), 0, 100);
        return collect($primaryData);
    }

}
