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
    }
}
