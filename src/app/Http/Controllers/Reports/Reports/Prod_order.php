<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;



class Prod_order extends Report_ParentController

{
    use TraitReport;
    public function getSqlStr($urlParams)
    {
        $sql = "SELECT 
                po.id AS po_id
                ,po.name AS po_name
                ,SUM(ps.total_hours) AS total_hours
                ,SUM(prd.target_hours) AS total_target_hours
                ,SUM(prd.target_hours) - SUM(ps.total_hours) AS total_interest_target_hours
                
                ,SUM(ps.total_man_hours) AS total_man_hours 
                ,SUM(prd.target_man_hours) AS total_target_man_hours
                ,SUM(prd.target_man_hours) - SUM(ps.total_man_hours) AS total_interest_target_man_hours
                
                #, prd.prod_routing_link_id
                FROM prod_orders po
                    LEFT JOIN prod_routing_details prd ON po.prod_routing_id = prd.prod_routing_id
                    LEFT JOIN prod_sequences ps ON po.id = ps.prod_order_id
                    WHERE 1 = 1 \n";
        // dump($urlParams);
        if (isset($urlParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}' \n";
        if (isset($urlParams['prod_order'])) $sql .= "\n AND po.id = '{{prod_order}}'\n ";
        $sql .=  "GROUP BY po.id";
        return $sql;
    }
    public function getTableColumns($dataSource = [])
    {
        return [
            [
                "title" => 'Prod Order ID',
                "dataIndex" => "po_id",
                "renderer" => "id",
                "align" => "center",
                "type" => "prod_orders",

            ],
            [
                "title" => 'Prod Order Name',
                "dataIndex" => "po_name",
            ],
            [
                "dataIndex" => "total_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "total_target_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "total_interest_target_hours",
                'align' => 'right',
            ],
            [
                "dataIndex" => "total_man_hours",
                'align' => 'right',
            ],
            [
                "dataIndex" => "total_target_man_hours",
                'align' => 'right',
            ],
            [
                "dataIndex" => "total_interest_target_man_hours",
                'align' => 'right',
            ],
        ];
    }
    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prod_orders  = ['prod_order' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $prod_orders);
    }
    protected function enrichDataSource($dataSource, $urlParams)
    {
        $isNullParams = $this->isNullUrlParams($urlParams);
        if ($isNullParams) {
            $dataSource->setCollection(collect([]));
            return $dataSource;
        };
        return $dataSource;
    }
}
