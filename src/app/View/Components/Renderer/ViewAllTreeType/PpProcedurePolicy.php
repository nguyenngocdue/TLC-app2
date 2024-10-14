<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use App\Models\Department;
use App\Utils\Support\CurrentUser;

class PpProcedurePolicy extends ViewAllTypeTreeExplorer
{
    protected $showSearch = true;

    protected function getApiRoute()
    {
        return route("pp_procedure_policy_tree_explorer");
    }

    private function getDepartments()
    {
        $departments = Department::query()
            ->where('hide_in_pp', 0)
            ->with(['getHOD' => fn($q) => $q->with(["getAvatar"])])
            ->orderBy('name')
            ->get();
        $result = [];
        foreach ($departments as $department) {
            $avatar = $department->getHOD->getAvatar?->url_thumbnail ?? '/images/avatar.jpg';
            $src = "<img class='rounded-full ml-2 mr-2' heigh=24 width=24 src='" . app()->pathMinio() . $avatar . "' />";
            $result[] = [
                "id" => 'department_' . $department->id,
                "text" => "<span class='flex'>" . $src . $department->name . "</span>",
                'parent' => 'procedure',
                "data" => [
                    "item_id" => $department->id,
                    "parent_01" => 'department',
                    "draggable" => false,
                ],
                'icon' => false,
            ];
        }
        return $result;
    }

    private function getProcedureFiles()
    {
        $procedureItems = \App\Models\Pp_procedure_policy::query()
            ->orderBy('name')
            ->get();
        $result = [];
        foreach ($procedureItems as $procedure) {
            if (!$procedure->department_id) continue;
            $route = (CurrentUser::isAdmin()) ? route('pp_procedure_policies.edit', $procedure->id) : '';
            $link = $route ? "<a href='$route' class='text-blue-400 ml-2' target='_blank'><i class='fa-regular fa-edit'></i></a>" : '';
            $result[] = [
                "id" => $procedure->id,
                "text" => $procedure->name . $link,
                'parent' => 'department_' . $procedure->department_id,
                'icon' => "fa-regular fa-file text-blue-400",
                "data" => [
                    "item_id" => $procedure->id,
                    "droppable" => false,
                    // "parent_01" => '',
                ],
            ];
        }
        return $result;
    }

    protected function getTree()
    {

        $roots = [
            [
                "id" => 'policy',
                "text" => "Cooperate Policies",
                'parent' => '#',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
            ],
            [
                "id" => 'procedure',
                "text" => "Department Procedures",
                'parent' => '#',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
            ],
        ];

        return [
            ...$roots,
            ...$this->getDepartments(),
            ...$this->getProcedureFiles(),
        ];
    }
}
