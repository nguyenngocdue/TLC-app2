<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController2;

class Eco_sheet_110 extends Report_ParentReportController2
{

    public function getSqlStr($params)
    {
        [$month, $projectId] = $this->selectMonth($params);
        $sql = "SELECT
                    SUBSTR(ecoli.updated_at, 1,7) AS month
                    ,ecos.project_id AS ecos_project_id
                    ,ecoli.department_id AS department_id
                    ,dp.name AS department_name
                    ,SUM(ecoli.head_count) AS head_count
                    ,SUM(ecoli.man_day) AS man_day
                    ,SUM(ecoli.labor_cost) AS labor_cost
                    ,ROUND(SUM(ecoli.head_count)*SUM(ecoli.man_day)*SUM(ecoli.labor_cost),2) AS total_cost
                    FROM eco_labor_impacts ecoli
                    LEFT JOIN eco_sheets ecos ON ecos.id = ecoli.eco_sheet_id
                    LEFT JOIN departments dp ON dp.id = ecoli.department_id
                    WHERE SUBSTR(ecoli.updated_at, 1,7) = '$month'
                    AND ecos.project_id = $projectId
                    GROUP BY department_id, ecos_project_id, month";
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

        ];
    }
}
