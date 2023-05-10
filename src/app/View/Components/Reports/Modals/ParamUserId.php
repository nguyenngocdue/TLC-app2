<?php

namespace App\View\Components\Reports\Modals;

use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Models\Sub_project;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use App\View\Components\Reports\ParentTypeParamReport;

class ParamUserId extends ParentTypeParamReport
{

    protected function getDataSource()
    {
        $currentUser = CurrentUser::get();
        [$viewport_uids, $leaf_uids] = [$currentUser['viewport_uids'], $currentUser['leaf_uids']];
        $treeData = BuildTree::getTreeByOptions(CurrentUser::id(), $viewport_uids, $leaf_uids, false, true);
        $dataSource = [];
        foreach ($treeData as $value) {
            if (app()->isProduction()) {
                if (!$value->show_on_beta) {
                    $dataSource[] = ['id' => $value->id, 'name' => $value->name];
                }
            } else {
                $dataSource[] = ['id' => $value->id, 'name' => $value->name];
            }
        }
        return $dataSource;
    }
}
