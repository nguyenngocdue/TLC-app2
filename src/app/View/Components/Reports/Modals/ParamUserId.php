<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Sub_project;
use App\Models\User;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamUserId extends ParentTypeParamReport
{
    protected function getDataSource()
    {
        $list = User::query();
        if (app()->isProduction()) $list = $list->where('show_on_beta', 0);
        $list = $list->get()->toArray();

        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
