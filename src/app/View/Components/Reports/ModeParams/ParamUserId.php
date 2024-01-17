<?php

namespace App\View\Components\Reports\ModeParams;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Utils\Support\CurrentUser;
use App\View\Components\Reports\ParentParamReports;

class ParamUserId extends ParentParamReports
{
    use TraitUserCompanyTree;
    public function getDataSource()
    {
        $treeData = $this->getDataByCompanyTree();
        $dataSource = [];
        $isAdmin = CurrentUser::isAdmin();
        foreach ($treeData as $value) {
            $name = $value->resigned ? $value->name0 . ' (RESIGNED)' : $value->name0;
            $name = $value->show_on_beta ? $name . ' (BETA)' : $name;
            $addId = $isAdmin ? '(#'.$value->id.')' : '';
            $dataSource[] = ['id' => $value->id, 'name' => $name.' '.$addId] ;
        }
        return $dataSource;
    }
}
