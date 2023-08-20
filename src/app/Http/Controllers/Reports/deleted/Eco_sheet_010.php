<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentController;

class Eco_sheet_010 extends Report_ParentController
{

    protected $groupBy = 'ot_date';
    protected $mode = '010';
    protected $maxH = 50;
    protected $tableTrueWidth = false;

    public function getSqlStr($modeParams)
    { 
        $sql ="SELECT
                    SUBSTR(ecoli.updated_at, 1,7) AS month
                    ,pj.name AS project_name
                    ,pj.name AS project_id
                    ,ecoli.department_id AS department_id
                    ,dp.name AS department_name
                    ,SUM(ecoli.head_count) AS head_count
                    ,SUM(ecoli.man_day) AS man_day
                    ,SUM(ecoli.labor_cost) AS labor_cost
                    ,ROUND(SUM(ecoli.head_count)*SUM(ecoli.man_day)*SUM(ecoli.labor_cost),2) AS total_cost
                    FROM eco_labor_impacts ecoli
                    LEFT JOIN eco_sheets ecos ON ecos.id = ecoli.eco_sheet_id
                    LEFT JOIN departments dp ON dp.id = ecoli.department_id
                    LEFT JOIN projects pj ON pj.id = ecos.project_id
                    WHERE SUBSTR(ecoli.updated_at, 1,7) = '2023-08'
                    GROUP BY department_id, project_name, month";
        
        return $sql;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        // dump($dataSource);
        return [
            [
                'title' => 'Department',
                "dataIndex" => "department_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                'title' => 'Headcounts (Man)',
                "dataIndex" => "head_count",
                "align" => 'right',
                "width" => 100,
            ],
            [
                'title' => 'Man-day (Day)',
                "dataIndex" => "man_day",
                "align" => 'right',
                "width" => 100,
            ],
            [
                'title' => 'Labor Cost (USD)',
                "dataIndex" => "labor_cost",
                "align" => 'right',
                "width" => 100,
            ],
            [
                'title' => 'Total Cost (USD)',
                "dataIndex" => "total_cost",
                "align" => 'right',
                "width" => 100,
            ],

        ];
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
}
