<?php

namespace App\View\Components\Reports\Modals;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\View\Components\Reports\ParentIdParamReports2;

class ParamManyUserId extends ParentIdParamReports2
{
    use TraitUserCompanyTree;
    protected function getDataSource($attr_name)
    {
        $treeData = $this->getDataByCompanyTree();
        $dataSource = [];
        foreach ($treeData as $value) {
            $dataSource[] = ['id' => $value->id, 'name' => $value->name];
        }
        return $dataSource;
    }
}
