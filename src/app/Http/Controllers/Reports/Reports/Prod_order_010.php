<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\Log;

class Prod_order_010 extends Report_ParentController

{
    use TraitReport;
    protected  $sub_project_id = 21;
    public function getSqlStr($modeParams)
    {
        // Log::info($modeParams);
        $sql = "SELECT 
     			sp.name AS sub_project_name
     			,po.sub_project_id AS sub_project_id
                ,po.id AS po_id
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
                    LEFT JOIN sub_projects sp ON sp.id = po.sub_project_id
                    WHERE 1 = 1 \n";
        if (empty($modeParams)) $sql  .= "AND po.sub_project_id =" . $this->sub_project_id;
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND po.sub_project_id = '{{sub_project_id}}' \n";
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'\n ";
        $sql .=  "\n GROUP BY po.id";
        return $sql;
    }
    public function getTableColumns($dataSource, $modeParams)
    {
        return [
            [
                "dataIndex" => "sub_project_name",
            ],
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

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
            ],
            [
                'title' => 'Production Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true
            ]
        ];
    }

    protected function getDataModes()
    {
        return ['mode_option' => ['010' => 'Model 010']];
    }


    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prod_orders  = ['prod_order_id' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $prod_orders);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        // dd($modeParams, $isNullModeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
        }
        return $modeParams;
    }
}
