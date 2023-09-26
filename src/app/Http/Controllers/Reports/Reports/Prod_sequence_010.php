<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DateReport;

class Prod_sequence_010 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $modeType = 'prod_sequence_010';
    protected $tableTrueWidth = false;
    protected $mode='010';
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;




    public function getDataSource($params)
    {
        $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
        // dd($primaryData);
        return collect($primaryData);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['month'] = DateReport::getCurrentYearAndMonth();
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
       # $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }


}
