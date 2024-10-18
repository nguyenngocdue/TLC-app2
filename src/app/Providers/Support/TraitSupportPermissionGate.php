<?php

namespace App\Providers\Support;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Models\User_discipline;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitSupportPermissionGate
{
    private function useTree()
    {
        if ($this->type == 'user_position') return false;
        return LibApps::getFor($this->type)['apply_approval_tree'] ?? false;
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
        $result = array_map(fn($item) => $item->id, $value);
        $result[] = $user->id;
        return array_unique($result) ?? [];
    }
    private function getTreeOwnerIds($user)
    {
        $value = $this->getCompanyTree($user, false);
        return $value;
    }
    private function isTreeUsed($type)
    {
        return LibApps::getFor($type)['apply_approval_tree'] ?? false;
    }

    private function checkPermission($permission)
    {
        return auth()->user()->roleSets[0]->hasPermissionTo($permission);
    }
    private function checkPermissionUsingGate($id, $action = 'edit', $restore = false)
    {
        // $item = $restore ? $this->modelPath::withTrashed()->findOrFail($id) : $this->modelPath::findOrFail($id);
        $item = $this->modelPath::query()
            ->where('id', $id);
        if ($restore) $item = $item->withTrashed();
        $item = $item->first();

        $isTree = $this->useTree();
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        if (CurrentUser::isAdmin()) {
            return $item;
        }
        $message = [];
        if ($isTree) {
            [$edit, $editOther] = $this->useTreeForPermissionTheLine($permissions, $item);
            $message[] = 'Edit denied';
            $message[] = $edit ? '' : 'You are not the document owner';
            $message[] = $editOther ? '' : "You are not in the approval tree";
            if (!($edit || $editOther)) return redirect($this->type . '.show'); //abort(403, join(' & ', $message));
        }
        return $item;
    }
    private function checkTree($item)
    {
        foreach ($this->getCompanyTree(CurrentUser::get()) as $value) {
            if ($item->owner_id == $value->id) {
                return true;
            }
        }
        return false;
    }
    private function checkPermissionUsingGateForDeleteMultiple($ids, $action = 'delete', $restore = false)
    {
        if (CurrentUser::isAdmin()) return []; // Admin is always can delete;
        $permissions = $this->permissionMiddleware[$action];
        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);
        // Log::info("Permissions " . join(",", $permissions));
        $arrFail = [];
        foreach ($ids as $id) {
            $item = $restore ? $this->modelPath::withTrashed()->findOrFail($id) : $this->modelPath::findOrFail($id);
            $isTree = $this->useTree($item);
            if ($isTree) {
                [$delete, $deleteOther] = $this->useTreeForPermissionTheLine($permissions, $item);
                if (!($delete || $deleteOther)) $arrFail[] = $id;
            }
            // Log::info("Result " . $isTree . " - " . $delete . " - " . $deleteOther);
        }
        $result = array_unique($arrFail) ?? [];
        return $result;
    }
    private function useTreeForPermissionTheLine($permissions, $item)
    {
        $result1 = false;
        $result2 = false;
        if ($this->checkPermission($permissions[0])) {
            $result1 = CurrentUser::id() == $item->owner_id;
        }
        if ($this->checkPermission($permissions[1])) {
            $result2 = (CurrentUser::id() == $item->owner_id) || $this->checkTree($item);
        }

        return [$result1, $result2];
    }
    private function getPositionsEntityUserPositionOfCurrentUser()
    {
        $currentUser = CurrentUser::get();
        $users = $currentUser->getPosition->getUsers;
        $positions = [];
        foreach ($users as $user) {
            $positions[] = $user->getPosition->name;
        }
        $disciplineIds = User_discipline::where("def_assignee", $currentUser->id)->pluck('id')->toArray();
        $users = User::whereIn("discipline", $disciplineIds)->get();
        foreach ($users as $user) {
            $positions[] = $user->getPosition->name;
        }
        $positions = array_unique($positions);
        return $positions;
    }

    private function checkIsExternalInspectorAndNominated($item)
    {
        $isExternalInspector = CurrentUser::get()->isExternalInspector();
        if (!$isExternalInspector) return;
        $nominatedList = $item->signature_qaqc_chklst_3rd_party_list->pluck('id');
        if (!$nominatedList->contains(CurrentUser::id())) {
            abort(403, "You are not permitted to view this check sheet (External Inspector has not yet nominated). If you believe this is a mistake, please contact our admin.");
        }
    }

    private function checkIsCouncilMemberAndNominated($item)
    {
        $isCouncilMember = CurrentUser::get()->isCouncilMember();
        if (!$isCouncilMember) return;
        $nominatedList = $item->council_member_list->pluck('id');
        if (!$nominatedList->contains(CurrentUser::id())) {
            abort(403, "You are not permitted to view this check sheet (Council Member has not yet nominated). If you believe this is a mistake, please contact our admin.");
        }
    }
}
