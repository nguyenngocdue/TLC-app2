<?php

namespace App\Providers\Support;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait TraitSupportPermissionGate
{
    private function editAndDelete($user, $model)
    {
        if (!CurrentUser::isAdmin()) {
            $isTree = $this->useTree($model);
            if ($isTree) {
                return true;
            }
            return $user->id == $model->owner_id;
        }
        return true;
    }
    private function editAndDeleteOther($user, $model)
    {
        if (!CurrentUser::isAdmin()) {
            $isTree = $this->useTree($model);
            if ($user->id == $model->owner_id) {
                return false;
            }
            if (!$isTree) {
                return $user->id == $model->owner_id;
            }
            foreach ($this->treeCompany($user) as $value) {
                if ($model->owner_id == $value->id) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
    private function useTree($model)
    {
        $type = Str::singular($model->getTable());
        return LibApps::getFor($type)['apply_approval_tree'] ?? false;
    }
    private function treeCompany($user)
    {
        return BuildTree::getTreeByOptions($user->id, $user->viewport_uids, $user->leaf_uids, false, true);
    }
    private function getListOwnerIds($user)
    {
        $value = $this->treeCompany($user);
        $result = array_map(fn ($item) => $item->id, $value);
        $result[] = $user->id;
        return array_unique($result) ?? [];
    }
    private function isUseTree($type)
    {
        return LibApps::getFor($type)['apply_approval_tree'] ?? false;
    }

    private function checkPermissionEdit($permission)
    {
        return auth()->user()->roleSets[0]->hasAnyPermission($permission);
    }
    private function checkPermissionUsingGate($id, $action = 'edit')
    {
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        $model = $this->data::findOrFail($id);
        switch (true) {
            case $this->checkPermissionEdit($permissions[0]):
                if (!Gate::allows($action, $model) || !Gate::allows($action . '-others', $model)) abort(403, "Permission denied " . $action);
                break;
            case $this->checkPermissionEdit($permissions[1]):
                if (!Gate::allows($action . '-others', $model)) abort(403, "Permission denied " . $action . "-others");
                break;
            default:
                break;
        }
        return $model;
    }
    private function checkPermissionUsingGateForDeleteMultiple($ids, $action = 'delete')
    {
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        $arrFail = [];
        foreach ($ids as $id) {
            $model = $this->data::findOrFail($id);
            switch (true) {
                case $this->checkPermissionEdit($permissions[0]):
                    if (!Gate::allows($action, $model) || !Gate::allows($action . '-others', $model)) $arrFail[] = $id;
                    break;
                case $this->checkPermissionEdit($permissions[1]):
                    if (!Gate::allows($action . '-others', $model)) $arrFail[] = $id;
                    break;
                default:
                    break;
            }
        }
        return array_unique($arrFail) ?? [];
    }
}
