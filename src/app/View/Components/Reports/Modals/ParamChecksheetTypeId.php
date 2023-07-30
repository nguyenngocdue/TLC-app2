<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Qaqc_insp_tmpl;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamChecksheetTypeId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = Qaqc_insp_tmpl::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        // dd($dataSource);
        return $dataSource;
    }
}
