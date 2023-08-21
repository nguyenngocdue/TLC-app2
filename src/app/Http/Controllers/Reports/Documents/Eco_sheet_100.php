<?php

namespace App\Http\Controllers\Reports\Documents;
use App\Utils\Support\DocumentReport;

trait Eco_sheet_100
{
    private function selectMonth($modeParams)
    {
        $month = DocumentReport::getCurrentMonthYear();
        $projectId = 5;
        if (isset($modeParams['month'])) {
            $month = $modeParams['month'];
        }
        if (isset($modeParams['project_id'])) {
            $projectId = $modeParams['project_id'];
        }
        return [$month, $projectId];
    }

    private function sqlEcoLaborImpacts($modeParams)
    {
        [$month, $projectId] = $this->selectMonth($modeParams);
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

    private function sqlEcoSheetsMaterialAdd($modeParams)
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
        // dump($sql);
        return $sql;
    }

    private function sqlEcoSheetsMaterialRemove($modeParams)
    {
        [$month, $projectId] = $this->selectMonth($modeParams);
        $sql = "SELECT 
                        ecos.id AS ecos_id
                        ,ecos.name AS ecos_name
                        ,ecos.project_id AS ecos_project_id
                        ,SUBSTR(ecos.created_at,1, 7) AS ecos_month
                        ,ecos.total_remove_cost AS ecos_total_remove_cost
                        ,ecos.status AS ecos_starus
                    FROM eco_sheets ecos
                    WHERE 1 = 1
                    AND SUBSTR(ecos.closed_at, 1, 7) = '$month'
                    AND ecos.project_id = $projectId
                    AND ecos.status = 'active'
                    GROUP BY ecos_id, ecos_name;";
        return $sql;
    }

    private function sqlCalculateTimeFromOpenEcoToSignOff($modeParams)
    {
        [$month, $projectId] = $this->selectMonth($modeParams);
        $sql = " SELECT 	
                    #ecosheet.ecos_id AS eco_sheet_id, 
                    usecos.term_id AS user_id,
                    us.name AS user_name,
                    SUM(ROUND(TIMESTAMPDIFF(HOUR, ecos_create_at,sign_updated_at)/24,2)) AS latency
                                FROM (SELECT 
                                ecos.id AS ecos_id
                                ,ecos.created_at AS ecos_create_at
                                FROM eco_sheets ecos
                                WHERE 1 = 1
                                AND ecos.project_id = $projectId
                                AND SUBSTR(ecos.created_at, 1, 7) = '$month'
                                #AND ecos.status = 'active'
                                ) AS ecosheet
                                LEFT JOIN ( SELECT *
                                                FROM many_to_many mtm
                                                WHERE 1 = 1 
                                                AND mtm.field_id = 31
                                                AND mtm.doc_type = 'App\\\Models\\\Eco_sheet') AS usecos ON usecos.doc_id =  ecosheet.ecos_id
                                LEFT JOIN (SELECT sign.id AS sign_id, sign.owner_id AS sign_ower_id, sign.signable_id AS ecos_id, sign.updated_at AS sign_updated_at
                                        FROM signatures sign
                                        WHERE 1 = 1
                                        AND sign.signable_type = 'App\\\Models\\\Eco_sheet') AS signeco ON signeco.ecos_id = ecosheet.ecos_id
                                        AND signeco.sign_ower_id = usecos.term_id
                                LEFT JOIN users us ON us.id = usecos.term_id
                                GROUP BY user_id,user_name
                                ORDER BY user_name";
        return $sql;
    }

    public function createArraySqlFromSqlStr($modeParams)
    {
        return [
            'getEcoLaborImpacts' => $this->sqlEcoLaborImpacts($modeParams),
            'getEcoSheetsMaterialAdd' => $this->sqlEcoSheetsMaterialAdd($modeParams),
            'getEcoSheetsMaterialRemove' => $this->sqlEcoSheetsMaterialRemove($modeParams),
            'getTimeEcoSheetSignOff' => $this->sqlCalculateTimeFromOpenEcoToSignOff($modeParams),
        ];
    }
}
