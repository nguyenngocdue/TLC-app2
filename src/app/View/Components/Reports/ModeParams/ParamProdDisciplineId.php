<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_discipline;
use App\Models\Prod_routing_link;
use App\View\Components\Reports\ParentParamReports;

class ParamProdDisciplineId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = Prod_discipline::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
