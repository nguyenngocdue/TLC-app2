<?php

namespace App\View\Components\Reports\ModeParams;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Utils\Support\CurrentUser;
use App\View\Components\Reports\ParentParamReports;

class ParamUserId extends ParentParamReports
{
    use TraitUserCompanyTree;
    protected $referData = 'department_id';
    protected $referData1 = 'workplace_id';
    public function getDataSource()
    {
        $treeData = $this->getDataByCompanyTree();
        $dataSource = [];
        $isAdmin = CurrentUser::isAdmin();
        foreach ($treeData as $value) {
            $name = $value->resigned ? $value->name0 . ' (RESIGNED)' : $value->name0;
            $name = $value->show_on_beta ? $name . ' (BETA)' : $name;
            $addId = $isAdmin ? '(#'.$value->id.')' : '';
            $dataSource[] = [
                'id' => $value->id, 
                'name' => $name.' '.$addId,
                'department_id' => $value->department,
                'workplace_id' => $value->workplace
                ] ;
        }
        // dd($dataSource);
        return $dataSource;
    }
}
