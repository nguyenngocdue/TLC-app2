<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Prod_order as ModelsProd_order;
use App\Models\Prod_routing_link;
use App\Models\Sub_project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Prod_sequence_010 extends Report_ParentController

{
    use TraitReport;
    use TraitForwardModeReport;
    protected  $sub_project_id = 21;
    protected $rotate45Width = 300;
    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
        sp.name AS sub_project_name, po.id AS po_id, po.name AS po_name, ps.id AS prod_sequence_id 
        , prl.name AS prod_routing_link_name
        ,SUM(ROUND(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) AS total_man_minute
        ,ROUND(SUM(pr.worker_number * TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60) / SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pr.end,pr.start))/60, 0)) ,2) AS workers
        FROM sub_projects sp, prod_orders po, prod_sequences ps, prod_runs pr, prod_routing_links prl
        WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (!isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id =" . $this->sub_project_id;
        if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        $sql .= "\n AND ps.prod_order_id = po.id
        AND ps.id = pr.prod_sequence_id
        AND prl.id = ps.prod_routing_link_id
        GROUP BY po_id, prod_sequence_id;";
        return $sql;
    }



    public function getTableColumns($dataSource, $modeParams)
    {
        // dd($dataSource);
        $editCols = [
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
        $unsetCols = [
            'prod_sequence_id', 'prod_routing_link_name',
            'po_id',
            'sequence_total_hours',
            'workers',
            'total_man_minute'
        ];
        $sqlDataCol = $this->createTableColumns($dataSource, '', '', $editCols, $unsetCols, 'right', '100');
        // dd($sqlDataCol);
        return  $sqlDataCol;
    }

    protected function getDataModes()
    {
        return ['mode_option' => ['010' => 'Model 010', '020' => 'Model 020']];
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
        $dataSource = Report::pressArrayTypeAllItems($dataSource);
        $groupProdOrders = Report::groupArrayByKey($dataSource, 'po_id');
        $groupSeqNames = array_map(function ($items) {
            array_walk($items, function ($value, $key) use (&$items) {
                $items[$key][Report::slugName($value['prod_routing_link_name'])] =
                    (object)[
                        'value' => $items[$key]['workers'],
                        'cell_title' => $items[$key]['prod_routing_link_name']
                    ];
            });
            return array_merge(...$items);
        }, $groupProdOrders);

        $allSeqNames = array_keys(array_merge(...array_values($groupSeqNames)));
        $dataSource = array_map(function ($item) use ($allSeqNames) {
            $diffItems = array_diff_key($allSeqNames, array_keys($item));
            array_walk($diffItems, function ($value, $key) use (&$item) {
                $item[$value] = '';
            });
            return $item;
        }, $groupSeqNames);
        // dd($dataSource['9']);
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
