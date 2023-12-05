<?php

namespace App\View\Components\Reports\ModeParams;

use App\Utils\Support\ParameterReport;
use App\View\Components\Reports\ParentParamReports;

class ParamProdDisciplineId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = ParameterReport::getDataForParameterReport('prod_discipline_id');
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
