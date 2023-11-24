<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Project;
use App\View\Components\Reports\ParentParamReports;

class ParamProjectId extends ParentParamReports
{
    protected function getDataSource()
    {
        $statuses = config("project")['active_statuses']['projects'];

        $list = Project::where('deleted_at', NULL)
                        ->when($statuses, function($query) use ($statuses) {
                            return $query->whereIn('status', $statuses);
                        })
                        ->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name'], 'description' => $team['description']];
        // dd($dataSource);
        return $dataSource;
    }
}
