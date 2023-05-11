<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Reports\TraitSQLDataSourceParamReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Report;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_020 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitFunctionsReport;
    use TraitModifyDataToExcelReport;
    use TraitForwardModeReport;
    use TraitSQLDataSourceParamReport;

    // protected $rotate45Width = 500;
    protected $maxH = 40;
    protected  $sub_project_id = 21;
    protected  $prod_routing_id = 2;
    protected  $mode = '020';


    public function getSqlStr($modeParams)
    {

        $plural = 'qaqc_wirs';
        $statuses = LibStatuses::getFor($plural);
        $statusNames = array_keys($statuses);

        $sql = "SELECT countStatus.*, count_prod_routing, (count_prod_routing - count_wir_created) AS count_wir_not_create
        #caculate status
           ,ROUND((count_null/count_prod_routing)*100, 2) AS percent_null
           ,ROUND(((count_prod_routing - count_wir_created)/count_prod_routing)*100, 2) AS percent_wir_not_create
           ,ROUND(((count_closed + count_not_applicable)/count_prod_routing)*100, 2) AS percent_completion
           ,count_null
           ";
        foreach ($statusNames as $status) {
            $sql .= "\n ,ROUND((" . "count_" . $status . "/" . "count_prod_routing)*100,2) AS percent_" . $status;
        }


        $sql .= "\n FROM(SELECT 
                sub_project_id, sub_project_name, prod_order_id, prod_order_name, prod_routing_id
                ,GROUP_CONCAT(wir_id) AS gp_wir_id
                ,COUNT(wir_id) AS count_wir_created";
        #list status
        foreach ($statusNames as $status) {
            $sql .= "\n ,SUM(statusesWirTb.wir_status IN ('" . $status . "')) AS count_" . $status;
        }
        $sql .= "\n ,SUM(statusesWirTb.wir_status IS NULL) AS count_null
                    FROM (SELECT 
                    potb.*
                    ,wir.id AS wir_id
                    ,wir.name AS wir_name 
                    ,wir.sub_project_id AS wir_sub_project 
                    ,wir.prod_order_id AS wir_prod_order_id 
                    ,wir.status AS wir_status
                    FROM (SELECT
                        pr.id AS project_id
                        ,pr.name AS project_name
                        ,sp.id AS sub_project_id
                        ,sp.name AS sub_project_name
                        ,po.id AS prod_order_id
                        ,po.name AS prod_order_name
                        ,po.prod_routing_id AS prod_routing_id
                        FROM projects pr, sub_projects sp, prod_orders po
                        WHERE 1 = 1
                            AND pr.id = sp.project_id
                            AND po.sub_project_id = sp.id
                            #AND po.id = 247";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        // if (isset($modeParams['prod_order_id'])) $sql .= "\n AND po.id = '{{prod_order_id}}'";
        if (isset($modeParams['prod_order_id'])) $sql = $this->sqlMultiSelectProdOrders($sql, $modeParams);
        $sql .= "\n ) AS potb
                            LEFT JOIN qaqc_wirs wir ON wir.sub_project_id = potb.sub_project_id 
                                                        AND wir.prod_order_id = potb.prod_order_id 
                                                        AND wir.prod_routing_id = potb.prod_routing_id ) AS statusesWirTb
                            GROUP BY sub_project_id, sub_project_name, prod_order_id, prod_order_name, prod_routing_id) AS countStatus,
                            (SELECT pr_id,COUNT(prtb.pr_id) AS count_prod_routing
                                FROM (SELECT wd.id as wir_description_id, wd.name AS wir_description_name, m2m.term_id pr_id
                                        FROM many_to_many m2m, wir_descriptions wd
                                        WHERE 1 =1 
                                        AND doc_type='App\\\Models\\\Wir_description'
                                        AND term_type='App\\\Models\\\Prod_routing'
                                        AND m2m.doc_id=wd.id ) AS prtb
                                        GROUP BY prtb.pr_id, pr_id ) AS prlst
                           WHERE 1 = 1
                           AND prlst.pr_id = countStatus.prod_routing_id
                           ORDER BY prod_order_name;";

        // dd($sql);
        return $sql;
    }


    protected function getTableColumns($dataSource, $modeParams)
    {

        $columns1 = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
            ],
            [
                "title" => "Prod Order Name",
                "dataIndex" => "prod_order_name",
                "align" => "center",
            ],
            [
                "title" => "WIR Completion (%)",
                "dataIndex" => "percent_completion",
                "align" => "right",
            ],
            [
                "title" => "Not yet started (%)",
                "dataIndex" => "percent_wir_not_create",
                "align" => "right",
            ],
        ];
        $plural = 'qaqc_wirs';
        $statuses = LibStatuses::getFor($plural);
        $statusNames = array_keys($statuses);
        $columns2 = array_map(fn ($item) => [
            'title' => Report::makeTitle($item) . ' (%)',
            'dataIndex' => 'percent_' . $item,
            'align' => 'right',
        ], $statusNames);



        $dataColumn = array_merge($columns1, $columns2);
        // dd($dataColumn);
        return  $dataColumn;
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
                'allowClear' => true,
                'multiple' => true
            ]
        ];
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        return $dataSource;
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        $dataSource = $dataSource->ToArray() ?? $dataSource;
        array_walk($dataSource, function ($value) {
            $value->prod_order_name = (object)[
                'value' => $value->prod_order_name,
                'cell_title' => 'ID: ' . $value->prod_order_id,
            ];
        });
        // dd($dataSource[0]);
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        // dd($modeParams);
        $x = 'sub_project_id';
        $z = 'prod_routing_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->sub_project_id;
            $modeParams[$z] = $this->prod_routing_id;
        }
        // dd($modeParams);
        return $modeParams;
    }
}
