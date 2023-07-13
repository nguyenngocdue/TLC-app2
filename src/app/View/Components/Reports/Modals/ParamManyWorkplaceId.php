<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Workplace;
use App\View\Components\Reports\ParentIdParamReports2;

class ParamManyWorkplaceId extends ParentIdParamReports2
{
    protected function getDataSource($attr_name)
    {
        $list = Workplace::where('deleted_at', NULL)-> get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
