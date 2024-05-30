<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Models\User_discipline;
use Illuminate\View\Component;

class ViewAllTypeTreeExplorer extends Component
{
    private function getImageFromSrc($src)
    {
        return  "<img class='rounded mx-1' height='24' width='24' src='$src'></img>";
    }

    function getDepartmentTree()
    {
        $getUserFn = function ($query) {
            $query
                ->with("getAvatar")
                ->where('resigned', false)
                ->where('time_keeping_type', 2);
        };
        $disciplines = User_discipline::query()
            ->with(["getDefAssignee" => function ($query) {
                $query->with(["getUserDepartment" => function ($query) {
                    $query->with("getHOD");
                }]);
            }])
            ->whereHas("getUsers", $getUserFn)
            ->with(["getUsers" => $getUserFn])
            ->get();

        $departments = [];
        $departmentIds = [];
        foreach ($disciplines as $discipline) {
            if ($discipline->getDefAssignee) {
                $defaultAssignee = $discipline->getDefAssignee;
                $department = $defaultAssignee->getUserDepartment;
                $departmentId = $department->id;
                $departmentName = $department->name;
                $departmentIds[] = $departmentId;
                $departments[$departmentId]['id'] = $departmentName; // It sort by ID

                $departments[$departmentId]['name_for_sort'] =  $departmentName; // to sort
                if ($department->getHOD) {
                    // $departments[$departmentId]['icon'] = app()->pathMinio() . rawurlencode($department->getHOD->getAvatar->url_thumbnail);
                    $departments[$departmentId]['icon'] = false;
                    $icon = app()->pathMinio() . rawurlencode($department->getHOD->getAvatar->url_thumbnail);
                    $img = $this->getImageFromSrc($icon);
                    // $img = "<img class='rounded mx-1' height='24' width='24' src='$icon'></img>";
                    $newText = $img . $departmentName;
                    $newText = "<div class='flex'>$newText</div>";
                    $departments[$departmentId]['text'] = $newText;
                }

                $disciplineItem = [
                    'id' => $discipline->id,
                    'name_for_sort' => $discipline->name,
                    'text' => $discipline->name,
                    'icon' => false,
                ];

                $imgs = [];
                foreach ($discipline->getUsers as $user) {
                    if ($user->getAvatar) {
                        $icon = app()->pathMinio() . rawurlencode($user->getAvatar->url_thumbnail);
                        $imgs[] = $this->getImageFromSrc($icon);
                    } else {
                        $imgs[] = $this->getImageFromSrc("/images/avatar.jpg");
                    }
                }

                $disciplineItem['text'] = "<div class='flex'>" . $disciplineItem['text'] . join("", $imgs) . "</div>";

                $departments[$departmentId]['children'][] = $disciplineItem;
            }
        }

        $departmentIds = array_values(array_unique($departmentIds));
        for ($i = 0; $i < sizeof($departmentIds); $i++) {
            if ($departments[$departmentIds[$i]]['children']) {
                uasort($departments[$departmentIds[$i]]['children'], function ($a, $b) {
                    return $a['name_for_sort'] <=> $b['name_for_sort'];
                });
            }
            $departments[$departmentIds[$i]]['children'] = array_values($departments[$departmentIds[$i]]['children']);
        }

        uasort($departments, function ($a, $b) {
            return $a['name_for_sort'] <=> $b['name_for_sort'];
        });

        $departments = array_values($departments);
        // dump($departments);
        return $departments;
    }

    function render()
    {
        return view('components.renderer.view-all.view-all-type-tree-explorer', [
            'tree' => $this->getDepartmentTree(),
        ]);
    }
}
