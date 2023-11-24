<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateBasicInfoDataSource;
use App\Utils\Support\DateReport;


class Prod_sequence_020 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitUpdateBasicInfoDataSource;

    protected $mode='020';
    protected $modeType = 'prod_sequence_020';
    protected $typeView = 'report-pivot';
    protected $pageLimit = 10;
    protected $tableTrueWidth = true;
    protected $maxH = 30;
    #protected $projectId = 8;
    #protected $subProjectId = 107;
    #protected $prodRoutingId = 62;

    public function getDataSource($params)
    {
        $primaryData = (new Prod_sequence_dataSource())->getDataSource($params);
        // dd($primaryData);
        return collect($primaryData);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate();
        #$params['project_id'] = $this->projectId;
        #$params['sub_project_id'] = $this->subProjectId;
        #$params['prod_routing_id'] = $this->prodRoutingId;
        // dd($params);
        return $params;
    }
}
