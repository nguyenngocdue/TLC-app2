<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_routing_link;
use App\View\Components\Reports\ParentParamReports;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamProdRoutingLinkId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = Prod_routing_link::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
