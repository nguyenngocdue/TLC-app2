<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_discipline;
use App\Models\Prod_routing_link;
use App\Utils\Support\ParameterReport;
use App\View\Components\Reports\ParentParamReports;

class ParamProdDisciplineId extends ParentParamReports
{
    protected function getDataSource()
    {
        $configData = ParameterReport::getConfigByName();
        $targetIds = ParameterReport::getTargetIds($configData);
        $list = ParameterReport::getDBParameter($targetIds, 'Prod_discipline', true);
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
