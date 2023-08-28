<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\User_team_site;
use App\View\Components\Reports\ParentParamReports;
use Illuminate\Support\Facades\DB;

class ParamUserTeamSiteId extends ParentParamReports
{
    protected function getDataSource()
    {
        $list = User_team_site::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name'], 'description' => $team['description']];
        return $dataSource;
    }
}
