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
        $maxAvatar = 40;
        $roleSets = RoleSet::query()
            ->with(['users' => function ($q) {
                $q //->where('show_on_beta', 1)
                    ->where('resigned', 0)
                    ->with([
                        'getUserDepartment',
                        'getAvatar',
                        'getUserDiscipline',
                    ])
                    ->orderBy('name0');
            }])
            ->orderBy('name')
            ->get();

        $departments = [];
        foreach ($roleSets as $roleSet) {
            $users = $roleSet->users;
            foreach ($users as $user) {
                if ($user->show_on_beta == 1) {
                    if ($user->department) {
                        $departments[$user->department] = $user->getUserDepartment;
                    }
                }
            }
        }

        usort($departments, function ($a, $b) {
            return $a->name <=> $b->name;
        });

        foreach ($departments as $department) {
            $count = 0;
            foreach ($roleSets as $roleSet) {
                $users = $roleSet->users;
                $userOfDepartment = [];
                foreach ($users as $user) {
                    if ($user->department != $department->id) continue;
                    if ($user->show_on_beta != 1) continue;
                    $userOfDepartment[] = $user;
                    $idStr = Str::makeId($user->id);
                    $href = route('users.edit', $user->id);
                    $idStr = " (<a href='$href' class='text-blue-500'>$idStr</a>)";
                    $disciplineStr = $user->getUserDiscipline->name;

                    $jsonTree[] = [
                        'id' => $user->id,
                        'text' => $user->name . " - " . $user->email . " - " . $idStr . " - Discipline: " . $disciplineStr,
                        'parent' =>  $department->id . "roleSet_" . $roleSet->id,
                    ];
                }

                if (count($userOfDepartment)) {
                    $img = [];
                    $users_show_on_app = ($users->where('show_on_beta', 0));
                    $avatarCount = 0;
                    foreach ($users_show_on_app as $user) {
                        if ($avatarCount++ >= $maxAvatar) break;
                        $src = $user->getAvatar ? app()->pathMinio() . $user->getAvatar->url_thumbnail : "/images/avatar.jpg";
                        $route = route('users.edit', $user->id);
                        $name = $user->name . " - #" . $user->id;
                        $img[] = "<a href='$route' title='$name'><img src='$src' class='w-8 h-8 rounded-full border-2 border-white'></a>";
                    }
                    if ($users_show_on_app->count() > $maxAvatar) {
                        $img[] = " <a href='#' class='text-blue-500'> and " . ($users_show_on_app->count() - $maxAvatar) . "more ...</a>";
                    }

                    $jsonTree[] = [
                        'id' => $department->id . "roleSet_" . $roleSet->id,
                        'text' => "<div class='flex -mt-7 ml-6 items-center'>" . $roleSet->name . " " . join(" ", $img) . "</div>",
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
