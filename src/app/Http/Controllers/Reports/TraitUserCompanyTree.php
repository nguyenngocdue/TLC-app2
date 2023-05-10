<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;

trait TraitUserCompanyTree
{
    protected function getDataByCompanyTree()
    {
        $currentUser = CurrentUser::get();
        [$viewport_uids, $leaf_uids] = [$currentUser['viewport_uids'], $currentUser['leaf_uids']];
        $treeData = BuildTree::getTreeByOptions(CurrentUser::id(), $viewport_uids, $leaf_uids, false, true);
        return $treeData;
    }
}
