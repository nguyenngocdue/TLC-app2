<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Prod_order;
use App\Models\Sub_project;

class Prod_routing_link extends Report_ParentController
{
    public function getSqlStr($urlParams)
    {
        $sql = "SELECT
                    ps.prod_routing_link_id AS prod_routing_link_id
                    ,prl.name AS prod_routing_link_name
                    ,us.id AS user_id
                    ,us.name AS user_name
                    ,SUM(ps.total_hours) AS total_experience_hours
                    FROM prod_sequences ps, prod_runs pr, prod_user_runs pur, users us, prod_routing_links prl
                    WHERE 1 = 1
                    AND prl.id = '{{prod_routing_link_id}}'
                    AND ps.prod_routing_link_id = prl.id
                    AND pr.prod_sequence_id = ps.id
                    AND pur.prod_run_id = pr.id
                    AND us.id = pur.user_id
                    
                    GROUP BY user_id
                    ORDER BY total_experience_hours DESC";
        return $sql;
    }
    public function getTableColumns($dataSource = [])
    {
        return [
            [
                "dataIndex" => "prod_routing_link_id",
                "renderer" => "id",
                "align" => "right",
                "type" => "prod_routing_links",
            ],
            [
                "dataIndex" => "prod_routing_link_name",
                "align" => "center",
            ],
            [
                "dataIndex" => "user_id",
                "renderer" => "id",
                "align" => "right",
                "type" => "users",
            ],
            [
                "dataIndex" => "user_name",
                "align" => "left",
            ],
            [
                "dataIndex" => "total_experience_hours",
                "align" => "right",
            ],
        ];
    }
    public function getDataForModeControl($dataSource = [])
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prod_orders  = ['prod_order' =>  Prod_order::get()->pluck('name', 'id')->toArray()];

        return array_merge($subProjects, $prod_orders);
    }
}
