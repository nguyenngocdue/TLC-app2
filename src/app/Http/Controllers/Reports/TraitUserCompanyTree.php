<?php

namespace App\Http\Controllers\Reports;

use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;

trait TraitUserCompanyTree
{
    protected function getDataByCompanyTree($dataSource)
    {
        $currentUser = CurrentUser::get();
        [$viewport_uids, $leaf_uids] = [$currentUser['viewport_uids'], $currentUser['leaf_uids']];
        $treeData = BuildTree::getTreeByOptions(CurrentUser::id(), $viewport_uids, $leaf_uids, false, true);
        $userIds = array_column($treeData, 'id');
        $data = [];
        foreach ($dataSource as $key => $value) {
            if (in_array($value->user_id, $userIds)) {
                $data[] = $value;
            }
        }
        return collect($data);
    }
}
