<?php

namespace App\View\Components\Reports\Modals;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamUserId extends ParentTypeParamReport
{
    use TraitUserCompanyTree;
    protected function getDataSource()
    {
        $treeData = $this->getDataByCompanyTree();
        $dataSource = [];
        foreach ($treeData as $value) {
            $dataSource[] = ['id' => $value->id, 'name' => $value->name];
        }
        return $dataSource;

        // $list = User::query();
        // if (app()->isProduction()) $list = $list->where('show_on_beta', 0);
        // $list = $list->get()->toArray();

        // $dataSource = [];
        // usort($list, fn ($a, $b) => $a['name'] <=> $b['name']);
        // foreach ($list as $team) $dataSource[] = ['id' => $team['id'], 'name' => $team['name']];
        // return $dataSource;
    }
}
