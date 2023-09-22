<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Eco_sheet_110;
use App\Http\Controllers\Reports\Reports\Eco_sheet_120;
use App\Http\Controllers\Reports\Reports\Eco_sheet_130;
use App\Http\Controllers\Reports\Reports\Eco_sheet_140;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Project;
use App\Utils\Support\Report;

class Eco_sheet_010 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;

    protected $mode = '010';
    protected $projectId = 5;
    protected $type = 'eco_sheet';
    protected $viewName = 'document-eco-sheet';
    protected $pageLimit = 1000;
    protected $month = '2023-07';

    protected function getParamColumns($dataSource, $modeType)
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

    public function getTableColumns($params, $dataSource)
    {
        return [
            "ecoLaborImpacts" => [
                [
                    "title" => "Department",
                    "dataIndex" => "department_name",
                    "align" => "left",
                ],
                [
                    "title" => "Headcounts (Man)",
                    "dataIndex" => "head_count",
                    "align" => "right",
                    "footer" => "agg_sum",
                ],
                [
                    "title" => "Man-day (Day)",
                    "dataIndex" => "man_day",
                    "align" => "right",
                    "footer" => "agg_sum",
                ],
                [
                    "title" => "Labor Cost (USD)",
                    "dataIndex" => "labor_cost",
                    "align" => "right",
                    "footer" => "agg_sum",
                ],
                [
                    "title" => "Total Cost (USD)",
                    "dataIndex" => "total_cost",
                    "align" => "right",
                    "footer" => "agg_sum",
                ],
            ],
            "ecoSheetsMaterialAdd" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left",

                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_add_cost",
                    "align" => "right",
                    "footer" => "agg_sum",

                ],
            ],
            "ecoSheetsMaterialRemove" => [
                [
                    "title" => "ECO",
                    "dataIndex" => "ecos_name",
                    "align" => "left"
                ],
                [
                    "title" => "Total Cost",
                    "dataIndex" => "ecos_total_remove_cost",
                    "align" => "right",
                    "footer" => "agg_sum",
                ],
            ],
            "timeEcoSheetSignOff" => [
                [
                    "title" => "User to Sign",
                    "dataIndex" => "user_name",
                    "align" => "left"
                ],
                [
                    "title" => "Latency (days)",
                    "dataIndex" => "latency",
                    "align" => "right",
                    "footer" => "agg_sum",
                ]
            ]
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['month'] = $this->month ?? date("Y-m");
        $params['project_id'] = $this->projectId;
        return $params;
    }

    public function getBasicInfoData($params)
    {
        $month = $params['month'] ?? $this->month ?? date("Y-m");
        $projectName = Project::find($params['project_id'] ?? $this->projectId)->name;
        return [
            'month' => $month,
            'project_name' => $projectName
        ];
    }

    public function getDisplayValueColumns()
    {
        return [
            'ecoSheetsMaterialAdd' => [
                'ecos_name' => [
                    'route_name' => 'eco_sheets.edit'
                ],
                'ecos_total_add_cost' => [
                    'route_name' => 'eco_sheets.edit'
                ]
            ],
            'ecoSheetsMaterialRemove' => [
                'ecos_name' => [
                    'route_name' => 'eco_sheets.edit'
                ]
            ],
            'timeEcoSheetSignOff' => [
                'user_name' => [
                    'route_name' => 'users.edit'
                ]
            ]
        ];
    }

    public function getDataSource($params)
    {
        return [
            'ecoLaborImpacts' => (new Eco_sheet_110())->getDataSource($params),
            'ecoSheetsMaterialAdd' => (new Eco_sheet_120())->getDataSource($params),
            'ecoSheetsMaterialRemove' => (new Eco_sheet_130())->getDataSource($params),
            'timeEcoSheetSignOff' => (new Eco_sheet_140())->getDataSource($params),
        ];
    }
}
