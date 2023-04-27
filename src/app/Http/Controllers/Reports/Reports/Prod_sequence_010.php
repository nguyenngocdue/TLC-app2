<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Utils\Support\Report;

class Prod_sequence_010 extends  Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    use TraitSQLDataSourceParamReport;
    use TraitModifyDataToExcelReport;
    protected  $sub_project_id = 82;
    protected $prod_order_id = 238;
    protected  $prod_routing_id = 6;
    protected $rotate45Width = 400;
    protected $tableTrueWidth = true;


    private $x = 'sub_project_id';
    private $y = 'prod_order_id';
    private $z = 'prod_routing_id';

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        sp.name AS sub_project_name, po.id AS po_id, po.name AS po_name, ps.id AS prod_sequence_id 
        , prl.name AS prod_routing_link_name
        ,SUM(ROUND(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) AS total_man_hours
        ,ROUND(SUM(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60) / SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) ,2) AS workers
        FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl
        WHERE 1 = 1";
        if (isset($modeParams[$this->x])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams[$this->y])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        if (isset($modeParams[$this->z])) $sql .= "\n AND po.prod_routing_id = '{{prod_routing_id}}'";
        $sql .= "\n
        AND ps.prod_order_id = po.id
        AND sp.id = po.sub_project_id
        AND prl.id = ps.prod_routing_link_id
        AND ps.id = pr.prod_sequence_id
        GROUP BY po_id, prod_sequence_id
                     ORDER BY po_name";
        return $sql;
    }

    function get_variable_name($var, $scope = null)
    {
        if (null === $scope) {
            $scope = $GLOBALS;
        }
        $tmp = $var;
        $var = '__unique_var_name__' . mt_rand();
        $name = array_search($var, $scope, true);
        $var = $tmp;
        return $name;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        $firstCols = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
                "width" => 150,

            ],
            [
                "title" => "Prod Name",
                "dataIndex" => "po_name",
                "align" => "center",
                "width" => 150,
            ]
        ];
        $sqlDataCol = $this->createTableColumns($dataSource, 'workers', [], 'right', 50);
        return  array_merge($firstCols, $sqlDataCol);
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => $this->x,
            ],
            [
                'title' => 'Prod Order',
                'dataIndex' => $this->y,
            ],
            [
                'title' => 'Prod Routing',
                'dataIndex' => $this->z,
            ],

        ];
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
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$this->x] = $this->sub_project_id;
            $modeParams[$this->y] = $this->prod_order_id;
            $modeParams[$this->z] = $this->prod_routing_id;
        }
        return $modeParams;
    }
}
