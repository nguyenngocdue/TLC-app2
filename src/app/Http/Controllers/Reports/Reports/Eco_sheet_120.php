<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController2;

class Eco_sheet_120 extends Report_ParentReportController2
{

    public function getSqlStr($modeParams)
    {
        [$month, $projectId] = $this->selectMonth($modeParams);
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

    protected function getTableColumns($modeParams, $dataSource)
    {
        return [
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
        ];
    }
}
