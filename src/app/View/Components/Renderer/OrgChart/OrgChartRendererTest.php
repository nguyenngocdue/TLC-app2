<?php

namespace App\View\Components\Renderer\OrgChart;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Ndc\SpatieCustom\Models\RoleSet;

class OrgChartRendererTest extends Component
{
    function __construct(
        private $id = 0,
    ) {}

    public function render()
    {
        $roleSets = RoleSet::query()
            ->with(['users' => function ($q) {
                $q->where('show_on_beta', 1)
                    ->with('getUserDepartment')
                    ->orderBy('name0');
            }])
            ->orderBy('name')
            ->get();

        $departments = [];
        foreach ($roleSets as $roleSet) {
            $users = $roleSet->users;
            foreach ($users as $user) {
                if ($user->department) {
                    $departments[$user->department] = $user->getUserDepartment;
                }
            }
        }

        foreach ($departments as $department) {

            $count = 0;
            foreach ($roleSets as $roleSet) {
                $users = $roleSet->users;
                $userOfDepartment = [];
                foreach ($users as $user) {
                    if ($user->department != $department->id) continue;
                    $userOfDepartment[] = $user;
                    $idStr = Str::makeId($user->id);
                    $href = route('users.edit', $user->id);
                    $idStr = " (<a href='$href' class='text-blue-500'>$idStr</a>)";

                    $jsonTree[] = [
                        'id' => $user->id,
                        'text' => $user->name . " - " . $user->email . " - " . $idStr,
                        'parent' =>  $department->id . "roleSet_" . $roleSet->id,
                    ];
                }

                if (count($userOfDepartment)) {
                    $jsonTree[] = [
                        'id' => $department->id . "roleSet_" . $roleSet->id,
                        'text' => $roleSet->name,
                        'parent' => "department_" . $department->id,
                        'state' => ['opened' => true,],
                    ];
                    $count++;
                }
            }
            if ($count) {
                $jsonTree[] = [
                    'id' => "department_" . $department->id,
                    'text' => $department->name,
                    'parent' => "#",
                    'state' => ['opened' => true,],
                ];
            }
        }

        return view("components.renderer.org-chart.org-chart-renderer-external", [
            'id' => $this->id,
            'jsonTree' => $jsonTree,
        ]);
    }
}
