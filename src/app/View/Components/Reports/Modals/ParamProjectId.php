<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Project;
use App\View\Components\Reports\ParentParamReports;

class ParamProjectId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = Project::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
