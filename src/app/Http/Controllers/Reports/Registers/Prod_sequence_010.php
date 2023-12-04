<?php

namespace App\Http\Controllers\Reports\Registers;

use App\BigThink\HasStatus;
use App\Http\Controllers\Reports\Report_ParentRegister2Controller;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLegendReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\DateReport;

class Prod_sequence_010 extends Report_ParentRegister2Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;
    use HasStatus;
    use TraitLegendReport;
    protected $rotate45Width = 300;
    protected  $project_id = 8;
    protected  $sub_project_id = 107;
    protected  $prod_routing_id = 49;
    protected $mode = '010';
    protected $maxH = 45;
    protected $viewName = "register-prod-sequence-010";
    protected $sequence_mode = 1;

    public function getSqlStr($params)
    {
        $sql = "";
        return $sql;
    }
    public function getTableColumns($params, $dataSource)
    {
        return [[]];
    }

    protected function getParamColumns($dataSource, $modeType)
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
            [
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                // 'hasListenTo' => true,
            ],
            [
                'title' => 'Mode',
                'dataIndex' => 'sequence_mode',
            ],
            [
                'title' => 'Date to compare',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'singleDatePicker' => true,
                'validation' => 'required|date_format:d/m/Y',
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
        $params['picker_date'] = null;
        $params['sequence_mode'] = $this->sequence_mode;
        return $params;
    }
}
