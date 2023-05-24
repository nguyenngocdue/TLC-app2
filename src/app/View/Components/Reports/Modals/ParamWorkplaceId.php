<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Workplace;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamWorkplaceId extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $list = Workplace::where('deleted_by', NULL)-> get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
