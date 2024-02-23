<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Eco_sheet_120 extends Report_ParentReport2Controller
{
    public function getSqlStr($params)
    {
        [$month, $projectId] = $this->selectMonth($params);
        $sql = "SELECT 
                    ecos.id AS ecos_id
                    ,ecos.name AS ecos_name
                    ,ecos.project_id AS ecos_project_id
                    ,SUBSTR(ecos.created_at,1, 7) AS ecos_month
                    ,ecos.total_add_cost AS ecos_total_add_cost
                    ,ecos.total_remove_cost AS ecos_total_remove_cost
                    ,ecos.status AS ecos_starus
                FROM eco_sheets ecos
                WHERE 1 = 1
                AND SUBSTR(ecos.closed_at, 1, 7) = '$month'
                AND ecos.project_id = $projectId
                AND ecos.status = 'active'
                GROUP BY ecos_id, ecos_name";
        // dump($sql);
        return $sql;
    }

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

    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "ECO",
                "dataIndex" => "ecos_name",
                "align" => "left"
            ],
            [
                "title" => "Total Cost (USD)",
                "dataIndex" => "ecos_total_add_cost",
                "align" => "right",
                "footer" => "agg_sum"
            ],
        ];
    }
    public function getDisplayValueColumns()
    {
        return [
            [
                'ecos_name' => [
                    'route_name' => 'eco_sheets.edit'
                ],
            ]
        ];
    }
}
