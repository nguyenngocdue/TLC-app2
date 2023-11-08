<?php

namespace App\Http\Controllers\Reports\Registers;

use App\BigThink\HasStatus;
use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLegendReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;

class Qaqc_wir_010 extends Report_ParentRegisterController

{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;
    use HasStatus;
    use TraitLegendReport;
    protected $rotate45Width = 300;
    protected  $project_id = 8;
    protected  $sub_project_id = 107;
    protected  $prod_routing_id = 62;
    protected $mode = '020';
    protected $maxH = 45;
    protected $viewName="register-qaqc-wir-010";

    public function getSqlStr($params)
    {
        $sql = "";
        return $sql;
    }
    public function getTableColumns($dataSource, $params)
    {
        return [[]];
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'hasListenTo' => true,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_id',
                'allowClear' => true,
                'hasListenTo' => true,
            ],
        ];
    }

    
    protected function getColorLegends()
    {
        $plural = 'qaqc_insp_chklst_shts';
        $statuses = LibStatuses::getFor($plural);
        $legendData = [
            'legend_title' => 'Status Icon Legend',
            'legend_col' => 8,
            'dataSource' => $statuses
        ];
        return $this->createLegendData($legendData);
    }


    protected function getDefaultValueParams($params, $request)
    {
        $params['project_id'] = $this->project_id;
        $params['sub_project_id'] = $this->sub_project_id;
        $params['prod_routing_id'] = $this->prod_routing_id;
        return $params;
    }
}
