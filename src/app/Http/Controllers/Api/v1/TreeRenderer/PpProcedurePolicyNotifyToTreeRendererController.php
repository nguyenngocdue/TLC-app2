<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Department;
use App\Models\Pp_procedure_policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyNotifyToTreeRendererController //extends _TreeRendererController
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
            ->where('hide_in_pp', 0)
            ->with('getHOD')
            ->with(['getMembers' => function ($q) {
                $q->where('resigned', 0)
                    ->where('show_on_beta', 0)
                    ->where('email', 'like', '%@%')
                    ->with(['getAvatar']);
            }])
            ->orderBy('name')
            ->get();

        $tree = [];
        foreach ($departments as $department) {
            $avatar = $department->getHOD->getAvatar?->url_thumbnail ?? '/images/avatar.jpg';
            $src = "<img class='rounded-full ml-6 mr-2' heigh=24 width=24 src='" . app()->pathMinio() . $avatar . "' />";
            $tree[] = [
                'id' => "department_" . $department->id,
                // 'text' => "<span class='flex -mt-6'>" . $src . $department->name . "</span>",
                'text' => $department->name,
                'parent' => '#',
                'data' => ["type" => "department"],
                // 'icon' => false,
            ];
            $tree[] = [
                'id' => "hod_" . $department->getHOD->id,
                // 'text' => $department->getHOD->name,
                'text' => "<span class='flex -mt-6'>" . $src . $department->getHOD->name . "</span>",
                'parent' => "department_" . $department->id,
                'data' => ["type" => "hod"],
                'icon' => false,
            ];
            // Log::info($department->name . " " . $department->getMembers->count());
            if ($department->getMembers->count() > 1) {
                $tree[] = [
                    'id' => "members_of_" . $department->id,
                    'text' => "Members",
                    'parent' => "department_" . $department->id,
                    'data' => ["type" => "members"],
                    'state' => ["opened" => true],
                ];

                foreach ($department->getMembers as $member) {
                    if ($member->id == $department->head_of_department) continue;
                    $avatar = $member->getAvatar?->url_thumbnail ?? '/images/avatar.jpg';
                    $src = "<img class='rounded-full ml-6 mr-2' heigh=24 width=24 src='" . app()->pathMinio() . $avatar . "' />";

                    $tree[] = [
                        'id' => "member_" . $member->id,
                        // 'text' => $member->name,
                        'text' => "<span class='flex -mt-6'>" . $src . $member->name . "</span>",
                        'parent' => "members_of_" . $department->id,
                        'data' => ["type" => "member"],
                        'icon' => false,
                    ];
                }
            }
        }

        return $tree;
    }

    private function getNotifyToId($ppId)
    {
        $procedure = Pp_procedure_policy::query()->where('id', $ppId)->first();
        return [
            $procedure->getNotifyToHodExcluded->pluck('id')->toArray(),
            $procedure->getNotifyToMemberExcluded->pluck('id')->toArray(),
        ];
    }

    function renderToJson(Request $request)
    {
        $ppId = $request->input('ppId');
        [$notifyToHodExcluded, $notifyToMemberExcluded] = $this->getNotifyToId($ppId);
        // $jsonTree = $this->getNotifyToTree();

        $result = [
            'notifyToHodExcluded' => $notifyToHodExcluded,
            'notifyToMemberExcluded' => $notifyToMemberExcluded,
            // 'jsonTree' => $jsonTree,
        ];

        return $result;
    }
}
