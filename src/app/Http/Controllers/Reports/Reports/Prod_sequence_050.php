<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Prod_sequence_050 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    use TraitSQLDataSourceParamReport;


    protected $mode = '050';
    protected  $sub_project_id = 82;
    protected  $prod_routing_id = 6;

    public function getSqlStr($modeParams)
    {
        // dd($modeParams);
        $sql = "SELECT
        sub_project_name
        ,prod_sequence_id
        ,prod_sequence_name
        ,GROUP_CONCAT(distinct po_name SEPARATOR ', ') AS  po_name
        ,COUNT(distinct po_name ) AS  total_order
 
        ,format(SUM(total_uom),0) AS sum_total_uom
        ,format(SUM(sum_man_mins_X_total_uom),0) AS total_uom_man_hours_total
        ,format(SUM(sum_man_mins_X_total_uom) / SUM(total_uom) , 0) total_min
        
        ,format(ROUND((SUM(sum_man_mins_X_total_uom) / SUM(total_uom)),2),0)  AS total_min_sequence
        ,format(ROUND(((SUM(sum_man_mins_X_total_uom)/60) / SUM(total_uom)),2),0) AS total_hours_sequence
        ,ROUND((((SUM(sum_man_mins_X_total_uom)/60) / SUM(total_uom)))/8,2) AS total_day_sequence
        FROM (SELECT 
                sp.name AS sub_project_name 
                  ,prl.id AS prod_sequence_id
                ,prl.name AS prod_sequence_name
                ,po.name po_name
                ,ps.total_uom AS total_uom
                  ,GROUP_CONCAT(distinct po.name SEPARATOR ', ') AS  po_names
                ,(SUM(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))) * SUM(pr.worker_number)) *  ps.total_uom AS sum_man_mins_X_total_uom
                    FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl
                    WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id =" . $modeParams['sub_project_id'];
        if (!isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id =" . $this->sub_project_id;
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = '{{prod_routing_id}}'";
        if (!isset($modeParams['prod_routing_id'])) $sql .= "\n AND sp.id =" . $this->prod_routing_id;
        // dump($modeParams);
        $sql .= "\n     AND sp.id = po.sub_project_id
                        AND po.id = ps.prod_order_id
                        AND ps.prod_routing_link_id = prl.id
                        AND pr.prod_sequence_id = ps.id
                        AND ps.total_uom IS NOT NULL
                   GROUP BY prod_sequence_id, prod_sequence_name, po_name, total_uom) AS sub1
                   GROUP BY prod_sequence_id, prod_sequence_name;";
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
                "dataIndex" => "prod_sequence_name",
                "align" => "center",
                "width" => "300",
            ],
            [
                "dataIndex" => "total_order",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Total UOM",
                "dataIndex" => "sum_total_uom",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Total UoM Man-Hours",
                "dataIndex" => "total_uom_man_hours_total",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Min",
                "dataIndex" => "total_min",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Min",
                "dataIndex" => "total_min_sequence",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Hours",
                "dataIndex" => "total_hours_sequence",
                "align" => "center",
                "width" => "300",
            ],
            [
                "title" => "Average Days",
                "dataIndex" => "total_day_sequence",
                "align" => "center",
                "width" => "300",
            ]
        ];
        return  $dataCols;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
            ],

        ];
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        foreach ($dataSource as $key => $value) {
            $value->total_order = (object)[
                'value' => $value->total_order,
                'cell_title' => $value->po_name
            ];
            $value->sum_total_uom = (object)[
                'value' => $value->sum_total_uom,
                'cell_title' => "(Total UOM) = " . "Σ(Total UOM * Total Man Hours)"
            ];
            $value->total_uom_man_hours_total = (object)[
                'value' => $value->total_uom_man_hours_total,
                'cell_title' => "(Total UOM) = " . "Σ(Total UOM Sequences)"
            ];
            $value->total_min = (object)[
                'value' => $value->total_min,
                'cell_title' => "(Total Min) = " . "Total UoM Man-Hours / Total UOM"
            ];
            $value->total_min_sequence = (object)[
                'value' => $value->total_min_sequence,
                'cell_title' => "(Average Min) = " . "(Total Hours / Total Order)*60"
            ];
            $value->total_hours_sequence = (object)[
                'value' => $value->total_hours_sequence,
                'cell_title' => "(Average Hours) = " . "Average Min / 60"
            ];
            $value->total_day_sequence = (object)[
                'value' => $value->total_day_sequence,
                'cell_title' => "(Average Days) = " . "Average Hours / 8"
            ];
        }
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        $z = 'prod_order_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$y] = $this->prod_routing_id;
        }
        return $modeParams;
    }
}
