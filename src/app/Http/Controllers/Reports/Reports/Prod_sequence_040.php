<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Sub_project;
use App\Utils\Support\Report;

class Prod_sequence_040 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    use TraitSQLDataSourceParamReport;

    protected $mode = '040';
    protected  $sub_project_id = 21;
    protected $rotate45Width = 400;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        sp.name AS sub_project_name, po.id AS po_id, po.name AS po_name, ps.id AS prod_sequence_id 
        , prl.name AS prod_routing_link_name
        ,ps.total_uom AS total_uom
        ,ROUND((SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) * ROUND(SUM(pr.worker_number),2)), 2) AS total_man_minutes
        ,terms.name AS term_name
        ,ROUND((SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) * ROUND(SUM(pr.worker_number),2))/ps.total_uom , 2) AS min_uom
        FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl, terms
        WHERE 1 = 1";

        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        if (isset($modeParams['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = '{{prod_routing_id}}'";
        $sql .= "\n 
        AND sp.id = po.sub_project_id
        AND ps.prod_order_id = po.id
        #AND ps.total_uom IS NOT NULL
        AND ps.id = pr.prod_sequence_id
        AND prl.id = ps.prod_routing_link_id
        AND terms.id = ps.uom_id
        GROUP BY po_id, prod_sequence_id";
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
                "width" => "300",

            ],
            [
                "title" => "Prod Name",
                "dataIndex" => "po_name",
                "align" => "center",
                "width" => "300",
            ]
        ];
        $sqlDataCol = $this->createTableColumns($dataSource, 'min_uom');
        // dd($sqlDataCol);
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
                'title' => 'Prod Order',
                'dataIndex' => 'prod_order_id',
                'allowClear' => true
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => 'prod_routing_id',
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
            // dd($items);
            array_walk($items, function ($value, $key) use (&$items) {
                $items[$key][Report::slugName($value['prod_routing_link_name'])] =
                    (object)[
                        'value' => is_null($x = $items[$key]['min_uom']) ? 'null' : $x,
                        'cell_title' => $items[$key]['prod_routing_link_name'] . "\n[" . $x . '= Total Man-Minutes / Total UoM' . '] ' . 'Unit: min/UoM',
                        'cell_class' => is_null($x) ? 'bg-pink-400' : 'bg-green-50',
                    ];
            });
            // dd($items);
            return array_merge(...$items);
        }, $groupByProdOrders);
        $allSeqNamesReport = array_keys(array_merge(...array_values($enrichProdOrders)));
        $dataSource = array_map(function ($item) use ($allSeqNamesReport) {
            $diffItems = array_diff($allSeqNamesReport, array_keys($item));
            array_walk($diffItems, function ($value, $key) use (&$item) {
                $item[$value] = (object)[
                    'value' => '',
                    'cell_class' => 'bg-gray-600',
                    'cell_title' => 'Not included this run',
                ];
            });
            return $item;
        }, $enrichProdOrders);
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
