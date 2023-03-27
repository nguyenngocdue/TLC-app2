<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Prod_sequence_050 extends Report_ParentController

{
    use TraitReport;
    use TraitForwardModeReport;
    protected $mode = '050';
    protected  $sub_project_id = 21;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT sub_project_name ,po_id, po_name
        ,ROUND(SUM(subquery.min_uom),2) AS total_min_uom
        ,COUNT(subquery.min_uom) AS count_min_uom
        ,ROUND(SUM(subquery.min_uom)/COUNT(subquery.min_uom), 2) AS avg_minutes_on_run
        ,ROUND((SUM(subquery.min_uom)/COUNT(subquery.min_uom))/60, 2) AS avg_hours_on_run
        ,ROUND(((SUM(subquery.min_uom)/COUNT(subquery.min_uom))/60)*8.5, 2) AS avg_day_on_run
        FROM (
            SELECT 
                sp.name AS sub_project_name, po.id AS po_id, po.name AS po_name, ps.id prod_sequence_id
                ,ROUND((SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) * ROUND(SUM(pr.worker_number),2))/ps.total_uom , 2) AS min_uom
            FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl
            WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (!isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id =" . $this->sub_project_id;
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        $sql .= "\n AND ps.prod_order_id = po.id
                AND ps.id = pr.prod_sequence_id
                AND prl.id = ps.prod_routing_link_id
                AND ps.total_uom IS NOT NULL
            GROUP BY sub_project_name, po_id, prod_sequence_id
        ) AS subquery
        GROUP BY sub_project_name ,po_id";
        return $sql;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        // dd($dataSource);
        $dataCols = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
                "width" => "300",

            ],
            [
                "title" => "Prod Name",
                "dataIndex" => "po_name",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Total Tot Min UoM (Mins)",
                "dataIndex" => "total_min_uom",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Count Min UoM",
                "dataIndex" => "count_min_uom",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Mins (Mins)",
                "dataIndex" => "avg_minutes_on_run",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Hours (Hours)",
                "dataIndex" => "avg_hours_on_run",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Days (Hours/Day)",
                "dataIndex" => "avg_day_on_run",
                "align" => "center",
                "width" => "300",
            ]
        ];
        return  $dataCols;
    }

    protected function getDataModes()
    {
        return ['mode_option' => ['010' => 'Worker number', '020' => 'Working hours', '030' => 'Work Amount', '040' => 'min/UoM', '050' => 'Model 050']];
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

    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prodOrders  = ['prod_order_id' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        return array_merge($subProjects, $prodOrders);
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
        }
        return $modeParams;
    }
}
