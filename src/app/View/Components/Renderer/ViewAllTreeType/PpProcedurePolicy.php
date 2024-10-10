<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use App\Models\Department;

class PpProcedurePolicy extends ViewAllTypeTreeExplorer
{
    protected function getApiRoute()
    {
        return route("pp_procedure_policy_tree_explorer");
    }

    private function getDepartments()
    {
        $departments = Department::query()
            ->orderBy('name')
            ->get();
        $result = [];
        foreach ($departments as $department) {
            $result[] = [
                "id" => 'department_' . $department->id,
                "text" => $department->name,
                'parent' => 'procedure',
                "data" => [
                    "item_id" => $department->id,
                    "parent_01" => 'department',
                    "draggable" => false,
                ],
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
            $result[] = [
                "id" => $procedure->id,
                "text" => $procedure->name,
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
