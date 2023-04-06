<?php

namespace App\Providers\Support;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

trait TraitSupportPermissionGate
{
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

    private function checkPermission($permission)
    {
        return auth()->user()->roleSets[0]->hasAnyPermission($permission);
    }
    private function checkPermissionUsingGate($id, $action = 'edit')
    {
        $model = $this->data::findOrFail($id);
        $isTree = $this->useTree($model);
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        if (CurrentUser::isAdmin()) {
            return $model;
        }
        $message = [];
        if ($isTree) {
            [$edit, $editOther] = $this->useTreeForPermissionTheLine($isTree, $permissions, $model);
            $message[] = 'Permission edit denied';
            $message[] = $edit ? '' : 'You not able open edit because you not is owner id document';
            $message[] = $editOther ? '' : "You not able open edit because you not has in approval tree";
            if (!($edit || $editOther)) abort(403, join(' & ', $message));
        }
        return $model;
    }
    private function checkTree($model)
    {
        foreach ($this->treeCompany(CurrentUser::get()) as $value) {
            if ($model->owner_id == $value->id) {
                return true;
            }
        }
        return false;
    }
    private function checkPermissionUsingGateForDeleteMultiple($ids, $action = 'delete')
    {
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        $arrFail = [];
        if (CurrentUser::isAdmin()) {
            return $arrFail;
        }
        foreach ($ids as $id) {
            $model = $this->data::findOrFail($id);
            $isTree = $this->useTree($model);
            [$delete, $deleteOther] = $this->useTreeForPermissionTheLine($isTree, $permissions, $model);
            if (!($delete || $deleteOther)) $arrFail[] = $id;
        }
        return array_unique($arrFail) ?? [];
    }
    private function useTreeForPermissionTheLine($isTree, $permissions, $model)
    {
        $result1 = false;
        $result2 = false;
        if ($isTree) {
            switch (true) {
                case $this->checkPermission($permissions[0]):
                    $result1 = CurrentUser::id() == $model->owner_id;
                    break;
                case $this->checkPermission($permissions[1]):
                    $result2 = CurrentUser::id() == $model->owner_id || $this->checkTree($model);
                    break;
                default:
                    break;
            }
        }
        return [$result1, $result2];
    }
}
