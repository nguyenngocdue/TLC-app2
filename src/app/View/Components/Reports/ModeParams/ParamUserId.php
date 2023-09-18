<?php

namespace App\View\Components\Reports\ModeParams;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\View\Components\Reports\ParentParamReports;

class ParamUserId extends ParentParamReports
{
    use TraitUserCompanyTree;
    protected function getDataSource()
    {
        $treeData = $this->getDataByCompanyTree();
        $dataSource = [];
        foreach ($treeData as $value) {
            $name = $value->resigned ? $value->name0 . ' (RESIGNED)' : $value->name0;
            $name = $value->show_on_beta ? $name . ' (BETA)' : $name;
            $dataSource[] = ['id' => $value->id, 'name' => $name];
        }
        return $dataSource;
    }
}
