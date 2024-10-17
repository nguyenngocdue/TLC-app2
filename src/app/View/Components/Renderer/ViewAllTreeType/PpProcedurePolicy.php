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
                "id" => 'policy_1',
                "text" => "The TLC Charter",
                'parent' => 'policy',
                // 'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
            ],
            [
                "id" => 'iso',
                "text" => "ISO Policies & Procedures",
                'parent' => '#',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
            ],
            // [
            //     "id" => 'iso_1',
            //     "text" => "Policy Manuals",
            //     'parent' => 'iso',
            //     'state' => ['opened' => true],
            //     "data" => ['draggable' => false, 'droppable' => false],
            // ],
            [
                "id" => 'iso_1_01',
                "text" => "Policy Manuals",
                'parent' => 'iso',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
                "icon" => "fa-regular fa-file text-blue-400",
            ],
            // [
            //     "id" => 'iso_2',
            //     "text" => "Procedures",
            //     'parent' => 'iso',
            //     'state' => ['opened' => true],
            //     "data" => ['draggable' => false, 'droppable' => false],
            // ],
            [
                "id" => 'iso_2_01',
                "text" => "Procedures",
                'parent' => 'iso',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
                "icon" => "fa-regular fa-file text-blue-400",
            ],
            [
                "id" => 'iso_3',
                "text" => "Policy Statements",
                'parent' => 'iso',
                // 'state' => ['opened' => true],
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

        $policySubFolders = array_map(function ($item) {
            return [
                "id" => $item[array_key_first($item)],
                "text" => $item[array_key_first($item)],
                'parent' => 'policy_1',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
                "icon" => "fa-regular fa-file text-blue-400",
            ];
        }, [
            ['policy_1_00' => "00. Policy Templates"],
            ['policy_1_01' => "01. Fraud Prevention"],
            ['policy_1_02' => "02. Gifts and Entertainment"],
            ['policy_1_03' => "03. ESG"],
            ['policy_1_04' => "04. AML"],
            ['policy_1_05' => "05. Conflict of Interest"],
            ['policy_1_06' => "06. Code of Conduct"],
            ['policy_1_07' => "07. Risk Management"],
            ['policy_1_08' => "08. Whistle Blowing"],
            ['policy_1_09' => "09. IT Security"],
            ['policy_1_10' => "10. Retrenchment Policy"],
            ['policy_1_11' => "11. Grievance Handling Procedure"],
            ['policy_1_12' => "12. BCP"],
            ['policy_1_13' => "13. Energy Management Policy"],
            ['policy_1_14' => "14. Climate Vulnerability"],
            ['policy_1_15' => "15. ESMS"],
            ['policy_1_16' => "16. Water Management Policy"],
        ]);

        $statementSubFolders = array_map(function ($item) {
            return [
                "id" => $item[array_key_first($item)],
                "text" => $item[array_key_first($item)],
                'parent' => 'iso_3',
                'state' => ['opened' => true],
                "data" => ['draggable' => false, 'droppable' => false],
                "icon" => "fa-regular fa-file text-blue-400",
            ];
        }, [
            ['iso_3_01' => "01. EHS"],
            ['iso_3_02' => "02. Drug and Alcohol"],
            ['iso_3_03' => "03. Smoke Free Workplace"],
            ['iso_3_03' => "04. Injury Management"],
            ['iso_3_04' => "05. Quality Assurance"],
            ['iso_3_05' => "06. Equal Opportunity Employment"],
            ['iso_3_06' => "07. Fitness for Work"],
            ['iso_3_07' => "08. Our Safety Values"],
            ['iso_3_08' => "09. Labour Ethics Compliance"],
            ['iso_3_09' => "10. Environmental Mission"],
        ]);

        return [
            ...$roots,
            ...$policySubFolders,
            ...$statementSubFolders,
            ...$this->getDepartments(),
            ...$this->getProcedureFiles(),
        ];
    }
}
