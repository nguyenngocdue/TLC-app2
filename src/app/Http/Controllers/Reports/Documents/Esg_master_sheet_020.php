<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Eco_sheet_110;
use App\Http\Controllers\Reports\Reports\Eco_sheet_120;
use App\Http\Controllers\Reports\Reports\Eco_sheet_130;
use App\Http\Controllers\Reports\Reports\Eco_sheet_140;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Project;
use App\Utils\Support\Report;

class Esg_master_sheet_020 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;

    protected $mode = '010';
    protected $projectId = 5;
    protected $type = 'eco_sheet';
    protected $viewName = 'document-esg-master-sheet-020';
    protected $pageLimit = 1000;
    protected $month = '2023-07';

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ]
        ];
    }


    public function getDataSource($params)
    {
        return [
            
        ];
    }
}
