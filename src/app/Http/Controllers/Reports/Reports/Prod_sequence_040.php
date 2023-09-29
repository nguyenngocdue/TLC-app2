<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DateReport;

class Prod_sequence_040 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $mode='040';
    protected $modeType = 'prod_sequence_040';
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = false;
    protected $maxH = 50;
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
        $params['prod_routing_id'] = $this->prodRoutingId;
        return $params;
    }


}
