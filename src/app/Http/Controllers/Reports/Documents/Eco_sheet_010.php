<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Models\Project;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;


class Eco_sheet_010 extends Report_ParentController
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitModeParamsReport;
    use Eco_sheet_100;

    protected $mode = '010';
    protected $viewName = 'document-eco-sheet';
    protected $projectId = 5;

    public function getType()
    {
        return $this->getTable();
    }
    public function getSqlStr($modeParams) {
        return $this->createArraySqlFromSqlStr($modeParams);
    }

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ]
        ];
    }

    protected function getTableColumns($modeParams, $dataSource)
    {
        return [
            "getEcoLaborImpacts" => [
                [
                    "title" => "Department",
                    "dataIndex" => "department_name",
                    "align" => "left",
                ],
                [
                    "title" => "Headcounts (Man)",
                    "dataIndex" => "head_count",
                    "align" => "right",
                ],
                [
                    "title" => "Man-day (Day)",
                    "dataIndex" => "man_day",
                    "align" => "right",
                ],
                [
                    "title" => "Labor Cost (USD)",
                    "dataIndex" => "labor_cost",
                    "align" => "right",
                ],
                [
                    "title" => "Total Cost (USD)",
                    "dataIndex" => "total_cost",
                    "align" => "right",
                    // "footer" => "agg_sum"
                ],
            ],
            "getEcoSheetsMaterialAdd" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left"
                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_add_cost",
                    "align" => "right"
                ],
            ],
            "getEcoSheetsMaterialRemove" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left"

                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_remove_cost",
                    "align" => "right"

                ],
            ],
            "getTimeEcoSheetSignOff" => [
                [
                    "title" => "User to Sign",
                    "dataIndex" => "user_name",
                    "align" => "center"
                ],
                [
                    "title" => "Latency (days)",
                    "dataIndex" => "latency",
                    "align" => "right"
                ]
            ]
        ];
    }

    public function makeTitleForTables($modeParams)
    {
        $tableName = array_keys($this->getTableColumns($modeParams,[]));
        $name = ['Labor Impact', 'Material Impact Add', 'Material Impact Remove', 'Sign Off'];
        return array_combine($tableName, $name);
    }

    public function getBasicInfoData($modeParams)
    {
        $month = $modeParams['month'] ?? date("Y-m");
        $projectName = Project::find($modeParams['project_id'] ?? $this->projectId)->name;
        return [
            'month' => $month,
            'project_name' => $projectName
        ];
    }

    
}
