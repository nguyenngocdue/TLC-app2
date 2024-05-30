<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Models\User;
use App\Models\User_discipline;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class ViewAllTypeTreeExplorer extends Component
{
    private function getImageFromSrc($src, $title)
    {
        return  "<img title='$title' class='rounded-full mx-1' height='24' width='24' src='$src'></img>";
    }

    private function sortByName(&$departments, $departmentIds,)
    {
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
    }

    private function showOrphanTSO($usersInLeaf)
    {
        $getAllUserNeedTso = User::query()
            ->select('id', 'first_name', 'last_name')
            ->where('resigned', false)
            ->where('time_keeping_type', 2)
            ->get();

        $getAllUserNeedTsoIds = $getAllUserNeedTso->pluck('id')->toArray();
        $usersInLeaf = (collect($usersInLeaf)->flatMap(fn ($c) => $c));
        $usersInLeafIds = $usersInLeaf->pluck('id')->toArray();

        // dump(sizeof($getAllUserNeedTsoIds));
        // dump(sizeof($usersInLeafIds));

        $diffIds = (array_diff($getAllUserNeedTsoIds, $usersInLeafIds));
        $result = [];
        foreach ($diffIds as $id) {
            $user = $getAllUserNeedTso->where('id', $id)->first();
            $result[] = ($user->first_name . " " . $user->last_name . " (#" . $user->id . ")");
        }

        if (sizeof($result) > 0) {
            dump("ADMIN: The following users are required to submit TSO but not belong to discipline or department:");
            dump($result);
        }
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
            // ->where('show_in_task_budget', true)
            ->with(["getUsers" => $getUserFn])
            ->get();

        // dump(count($disciplines));

        $departments = [];
        $departmentIds = [];
        $usersInLeaf = [];
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
                    $user = $department->getHOD;
                    $departments[$departmentId]['icon'] = false;
                    $icon = app()->pathMinio() . rawurlencode($user->getAvatar->url_thumbnail);
                    $img = $this->getImageFromSrc($icon, $user->name);
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
                        $imgs[] = $this->getImageFromSrc($icon, $user->name);
                    } else {
                        $imgs[] = $this->getImageFromSrc("/images/avatar.jpg", $user->name);
                    }
                }

                $usersInLeaf[] = $discipline->getUsers;

                $disciplineItem['text'] = "<div class='flex'>" . $disciplineItem['text'] . join("", $imgs) . "</div>";

                $departments[$departmentId]['children'][] = $disciplineItem;
            }
        }

        if (CurrentUser::isAdmin()) $this->showOrphanTSO($usersInLeaf);

        $this->sortByName($departments, $departmentIds);

        $departments = array_values($departments);
        // dump($departments);
        return $departments;
    }

    function render()
    {
        return view('components.renderer.view-all.view-all-type-tree-explorer', [
            'tree' => $this->getDepartmentTree(),
            'route' => route("render_task_manager_tree_explorer"),
        ]);
    }
}
