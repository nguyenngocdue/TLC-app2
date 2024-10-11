<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Department;
use App\Models\Term;
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
                'id' => "department" . $department->id,
                'text' => "<span class='flex -mt-6'>" . $src . $department->name . "</span>",
                'parent' => '#',
                'data' => ["type" => "department"],
                'icon' => false,
            ];
            $tree[] = [
                'id' => "hod_of_" . $department->id,
                // 'text' => $department->getHOD->name,
                'text' => "<span class='flex -mt-6'>" . $src . $department->getHOD->name . "</span>",
                'parent' => "department" . $department->id,
                'data' => ["type" => "hod"],
                'icon' => false,
            ];
            // Log::info($department->name . " " . $department->getMembers->count());
            if ($department->getMembers->count() > 1) {
                $tree[] = [
                    'id' => "members_of_" . $department->id,
                    'text' => "Members",
                    'parent' => "department" . $department->id,
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

    function render(Request $request)
    {
        $versions = [
            [
                "fileName" => "file version 01.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
            [
                "fileName" => "file version 02.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
            [
                "fileName" => "file version 03.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
        ];
        $notifyTo = Term::query()
            ->where('field_id', 318)
            ->get();

        // Log::info($notifyTo);
        $notifyToTree = $this->getNotifyToTree();

        return view('components.renderer.view-all-tree-explorer.pp-procedure-policy', [
            'notifyTo' => $notifyTo,
            'notifyToTree' => $notifyToTree,
            'versions' => $versions,
        ]);
    }
}
