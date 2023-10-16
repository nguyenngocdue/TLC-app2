<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Qaqc_ncr_010 extends Report_ParentReport2Controller
{

    public function getSqlStr($params)
    {
        [$month, $projectId] = $this->selectMonth($params);
        $sql = "SELECT
                    SUBSTR(ecos.updated_at, 1,7) AS month
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
                    WHERE 1 = 1 
                    AND SUBSTR(ecos.updated_at, 1,7) = '$month'
                    AND ecos.project_id = $projectId
                    AND ecos.id = ecoli.eco_sheet_id
                    GROUP BY department_id, ecos_project_id, month";
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
            []

        ];
    }
   
}
