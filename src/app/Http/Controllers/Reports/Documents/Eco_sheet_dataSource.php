<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Controller;
use App\Utils\Support\DocumentReport;

Trait Eco_sheet_dataSource
{
    private function selectMonth($modeParams) {
        $month = DocumentReport::getCurrentMonthYear();
        if (isset($modeParams['month'])) {
            $month = $modeParams['month'];
        }
        return $month;
    }

    private function sqlStr1($modeParams)
    { 
        $month = $this->selectMonth($modeParams);
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
                    WHERE SUBSTR(ecoli.updated_at, 1,7) = '$month'
                    GROUP BY department_id, project_name, month";
        
        return $sql;
    }

    private function sqlStr2($modeParams)
    {
        $month = $this->selectMonth($modeParams);
        $sql ="SELECT 
                        ecos.id AS ecos_id
                        ,ecos.name AS ecos_name
                        ,SUBSTR(ecos.created_at,1, 7) AS ecos_month
                        ,ecos.total_add_cost AS ecos_total_add_cost
                        ,ecos.total_remove_cost AS ecos_total_remove_cost
                        ,ecos.status AS ecos_starus
                    FROM eco_sheets ecos
                    WHERE 1 = 1
                    AND SUBSTR(ecos.closed_at, 1, 7) = '$month'
                    AND ecos.status = 'active';";
        
        return $sql;
    }

    private function sqlStr3($modeParams)
    { 
        $month = $this->selectMonth($modeParams);
        $sql ="SELECT 
                        ecos.id AS scos_id
                        ,ecos.name AS scos_name
                        ,SUBSTR(ecos.created_at,1, 7) AS ecos_month
                        ,ecos.total_remove_cost AS ecos_total_remove_cost
                        ,ecos.status AS ecos_starus
                    FROM eco_sheets ecos
                    WHERE 1 = 1
                    AND SUBSTR(ecos.closed_at, 1, 7) = '$month'
                    AND ecos.status = 'active';";
        
        return $sql;
    }

    public function createArraySqlFromSqlStr($modeParams)
    {
       return [
        'getEcoLaborImpacts' => $this->sqlStr1($modeParams),
        'getEcoSheetsMaterialAdd' => $this->sqlStr2($modeParams),
        'getEcoSheetsMaterialRemove' => $this->sqlStr3($modeParams),
       ];
    }
}
