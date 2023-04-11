<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Prod_routing_link;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamProdRoutingLinkId extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $list = Prod_routing_link::get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
