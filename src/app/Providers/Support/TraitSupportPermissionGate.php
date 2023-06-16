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
    private function getCompanyTree($user, $flatten = true)
    {
        if (!$user->viewport_uids && !$user->leaf_uids && userIsAdmin($user)) {
            return BuildTree::getTree($flatten);
        }
        return BuildTree::getTreeByOptions($user->id, $user->viewport_uids, $user->leaf_uids, false, $flatten);
    }
    private function getListOwnerIds($user)
    {
        $value = $this->getCompanyTree($user);
        $result = array_map(fn ($item) => $item->id, $value);
        $result[] = $user->id;
        return array_unique($result) ?? [];
    }
    private function getTreeOwnerIds($user)
    {
        $value = $this->getCompanyTree($user, false);
        return $value;
    }
    private function isUseTree($type)
    {
        return LibApps::getFor($type)['apply_approval_tree'] ?? false;
    }

    private function checkPermission($permission)
    {
        return auth()->user()->roleSets[0]->hasPermissionTo($permission);
    }
    private function checkPermissionUsingGate($id, $action = 'edit', $restore = false)
    {
        $model = $restore ? $this->data::withTrashed()->findOrFail($id) : $this->data::findOrFail($id);
        $isTree = $this->useTree($model);
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        if (CurrentUser::isAdmin()) {
            return $model;
        }
        $message = [];
        if ($isTree) {
            [$edit, $editOther] = $this->useTreeForPermissionTheLine($isTree, $permissions, $model);
            $message[] = 'Edit denied';
            $message[] = $edit ? '' : 'You are not the document owner';
            $message[] = $editOther ? '' : "You are not in the approval tree";
            if (!($edit || $editOther)) abort(403, join(' & ', $message));
        }
        return $model;
    }
    private function checkTree($model)
    {
        foreach ($this->getCompanyTree(CurrentUser::get()) as $value) {
            if ($model->owner_id == $value->id) {
                return true;
            }
        }
        return false;
    }
    private function checkPermissionUsingGateForDeleteMultiple($ids, $action = 'delete', $restore = false)
    {
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        $arrFail = [];
        if (CurrentUser::isAdmin()) {
            return $arrFail;
        }
        foreach ($ids as $id) {
            $model = $restore ? $this->data::withTrashed()->findOrFail($id) : $this->data::findOrFail($id);
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
            if ($this->checkPermission($permissions[0])) {
                $result1 = CurrentUser::id() == $model->owner_id;
            }
            if ($this->checkPermission($permissions[1])) {
                $result2 = (CurrentUser::id() == $model->owner_id) || $this->checkTree($model);
            }
        }
        return [$result1, $result2];
    }
}
