<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Report;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Qaqc_ncr_010 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitFunctionsReport;
    use TraitModifyDataToExcelReport;

    protected $rotate45Width = 500;
    protected $maxH = 40;
    protected  $prod_routing_id = 2;


    public function getSqlStr($modeParams)
    {
        $sql = null;

        return $sql;
    }

    protected function getTableColumns($dataSource, $modeParams)
    {

        return  [];
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
            ]
        ];
    }


    protected function getDefaultValueModeParams($modeParams, $request)
    {

        return $modeParams;
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        return collect($dataSource);
    }
}
