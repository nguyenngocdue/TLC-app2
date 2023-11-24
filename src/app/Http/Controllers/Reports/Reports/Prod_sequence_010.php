<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_Parent2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DateReport;

class Prod_sequence_010 extends Report_Parent2Controller

{
    use TraitForwardModeReport;
    protected $mode='010';
    protected $modeType = 'prod_sequence_010';
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = true;
    protected $maxH = 50;
    // protected $projectId = 8;
    // protected $subProjectId = 107;
    #protected $prodRoutingId = 62;

    public function getDataSource($params)
    {
        $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
        // dump($primaryData, $params);
        return collect($primaryData);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate();
        // $params['project_id'] = $this->projectId;
        // $params['sub_project_id'] = $this->subProjectId;
        #$params['prod_routing_id'] = $this->prodRoutingId;
        // dd($params);
        return $params;
    }


}
