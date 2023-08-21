<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController2;

class Eco_sheet_140 extends Report_ParentReportController2
{

    public function getSqlStr($modeParams)
    {
        [$month, $projectId] = $this->selectMonth($modeParams);
        $sql = "SELECT 	
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
                "dataIndex" => "ecos_total_remove_cost",
                "align" => "right"

            ],
        ];
    }
}
