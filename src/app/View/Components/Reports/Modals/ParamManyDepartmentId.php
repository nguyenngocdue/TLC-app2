<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Department;
use App\View\Components\Reports\ParentIdParamReports2;

class ParamManyDepartmentId extends ParentIdParamReports2
{
    protected function getDataSource($attr_name)
    {
        $list = Department::where('deleted_at', NULL)->get()->toArray();
        $dataSource = [];
        usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        return $dataSource;
    }
}
