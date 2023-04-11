<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\Utils\ClassList;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamSubProjectId extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $list = Sub_project::get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
