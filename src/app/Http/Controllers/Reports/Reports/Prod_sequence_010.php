<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Prod_sequence_010 extends  Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    use TraitSQLDataSourceParamReport;
    protected  $sub_project_id = 21;
    protected  $prod_routing_id = 6;
    protected $rotate45Width = 400;


    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        sp.name AS sub_project_name, po.id AS po_id, po.name AS po_name, ps.id AS prod_sequence_id 
        , prl.name AS prod_routing_link_name
        ,SUM(ROUND(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) AS total_man_hours
        ,ROUND(SUM(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60) / SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) ,2) AS workers
        FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl
        WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = '{{prod_routing_id}}'";
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        $sql .= "\n
        AND ps.prod_order_id = po.id
        AND sp.id = po.sub_project_id
        AND prl.id = ps.prod_routing_link_id
        AND ps.id = pr.prod_sequence_id
        GROUP BY po_id, prod_sequence_id
                     ORDER BY po_name";
        return $sql;
    }



    public function getTableColumns($dataSource, $modeParams)
    {
        // dd($dataSource);
        $firstCols = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
                "width" => "200",

            ],
            [
                "title" => "Prod Name",
                "dataIndex" => "po_name",
                "align" => "center",
                "width" => "200",
            ]
        ];
        $sqlDataCol = $this->createTableColumns($dataSource, 'workers');
        return  array_merge($firstCols, $sqlDataCol);
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
            [
                'title' => 'Prod Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true
            ],

        ];
    }

    public function getDataForModeControl($dataSource)
    {
        $subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
        $prodOrders  = ['prod_order_id' =>  ModelsProd_order::get()->pluck('name', 'id')->toArray()];
        $prodRoutings = ['prod_routing_id' => array_column($this->getDataProdRouting(), 'prod_routing_name', 'prod_routing_id')];
        return array_merge($subProjects, $prodOrders, $prodRoutings);
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        $dataSource = Report::pressArrayTypeAllItems($dataSource);
        $groupByProdOrders = Report::groupArrayByKey($dataSource, 'po_id');
        $enrichProdOrders = array_map(function ($items) {
            array_walk($items, function ($value, $key) use (&$items) {
                $items[$key][Report::slugName($value['prod_routing_link_name'])] =
                    (object)[
                        'value' => $x = $items[$key]['workers'],
                        'cell_title' => $items[$key]['prod_routing_link_name'] . "\n" . $items[$key]["workers"] . ' (workers) = Σ(Duration) / Σ(Man-Minutes)',
                        'cell_class' => is_null($x) ? 'bg-pink-400' : 'bg-green-50',
                    ];
            });
            return array_merge(...$items);
        }, $groupByProdOrders);
        $allSeqNamesReport = array_keys(array_merge(...array_values($enrichProdOrders)));
        $dataSource = array_map(function ($item) use ($allSeqNamesReport) {
            $diffItems = array_diff($allSeqNamesReport, array_keys($item));
            array_walk($diffItems, function ($value, $key) use (&$item) {
                $item[$value] = (object)[
                    'value' => '',
                    'cell_class' => 'bg-gray-50',
                    'cell_title' => 'Not included this run',
                    'cell_div_class' => 'w-11',
                ];
            });
            return $item;
        }, $enrichProdOrders);
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'sub_project_id';
        $y = 'prod_routing_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$y] = $this->prod_routing_id;
        }
        return $modeParams;
    }
}
