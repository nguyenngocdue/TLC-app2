<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    function getUserBlock($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->getAvatar?->url_thumbnail ?? '/images/avatar.jpg'
        ];
    }

    function getNotifyToTree()
    {
        $departments = Department::query()
            ->with('getHOD')
            ->with(['getMembers' => function ($q) {
                $q->where('resigned', 0)
                    ->where('show_on_beta', 0)
                    ->where('email', 'like', '%@%')
                    ->with(['getAvatar']);
            }])
            ->get();

        $tree = [];
        foreach ($departments as $department) {
            $tree[] = [
                'id' => "department" . $department->id,
                'text' => $department->name,
                'parent' => '#',
                'data' => ["type" => "department"],
            ];
            $tree[] = [
                'id' => "hod_of_" . $department->id,
                'text' => $department->getHOD->name,
                'parent' => "department" . $department->id,
                'data' => ["type" => "hod"],
            ];
            $tree[] = [
                'id' => "members_of_" . $department->id,
                'text' => "Members",
                'parent' => "department" . $department->id,
                'data' => ["type" => "members"],
            ];
            foreach ($department->getMembers as $member) {
                if ($member->id == $department->head_of_department) continue;
                $tree[] = [
                    'id' => "member_" . $member->id,
                    'text' => $member->name,
                    'parent' => "members_of_" . $department->id,
                    'data' => ["type" => "member"],
                ];
            }
        }

        return $tree;
    }

    function render(Request $request)
    {
        $notifyTo = $this->getNotifyToTree();

        return view('components.renderer.view-all-tree-explorer.pp-procedure-policy', [
            'notifyTo' => $notifyTo,
        ]);
    }
}
