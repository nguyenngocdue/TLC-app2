<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;

trait TraitUserCompanyTree
{
    protected function getDataByCompanyTree()
    {
        if (CurrentUser::isAdmin()) {
            $uid = null;
            $viewport_uids = "";
            $leaf_uids  = "";
        } else {
            $uid = CurrentUser::id();
            $currentUser = CurrentUser::get();
            [$viewport_uids, $leaf_uids] = [$currentUser['viewport_uids'], $currentUser['leaf_uids']];
        }
        $treeData = BuildTree::getTreeByOptions($uid, $viewport_uids, $leaf_uids, false, true) ;

        
        if (app()->isProduction()) {
            foreach ($treeData as $value) {
                if (!$value->show_on_beta) $data[] = $value ;
            }
        } else {
            $data = $treeData;
        }
        $data = array_merge($data ,[(object)CurrentUser::get()->toArray()]);
        return  $data;
    }
}
