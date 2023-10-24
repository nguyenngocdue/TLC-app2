<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Qaqc_ncr_dataSource;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;

class Qaqc_wir_010 extends Report_ParentDocument2Controller
{

    protected $mode = '010';
    protected $viewName = 'document-wir-010';
    protected $tableTrueWidth = true;
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $pageLimit = 100000;


    private function generateCurrentAndPreviousDate($month){
        [$y, $m] = explode('-', $month);
        if($m === '01'){
            $y = $y - 1;
            $m = 12;
        };
        $previousMonth = str_pad($m, '2','0', STR_PAD_LEFT);
        $previousDate = $y."-".$previousMonth."-25";

        $latestDate = $month.'-'."25";
        if($latestDate > date("Y-m-d")) {
            $latestDate = date("Y-m-d");
            $previousDate = date("Y-m", strtotime($latestDate . " -1 month"))."-25";
        }
        return [$previousDate, $latestDate]; 
    }

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        $sql = " SELECT *
                            ,IF(total_prod_order_have_wir*100/(total_prod_order_on_sub_project*count_wir_description),
                                FORMAT(total_prod_order_have_wir*100/(total_prod_order_on_sub_project*count_wir_description),2)
                                ,NULL) AS latest_acceptance_percent
                            ,IF(total_prod_order_have_wir2*100/(total_prod_order_on_sub_project*count_wir_description),
                                FORMAT(total_prod_order_have_wir2*100/(total_prod_order_on_sub_project*count_wir_description), 2)
                                ,NULL) AS previous_acceptance_percent
                            FROM (SELECT
                                sp.project_id AS project_id,
                                sp.status AS sub_project_status,
                                pj.name AS project_name,
                                sp.id AS sub_project_id,
                                sp.name AS sub_project_name,
                                pr.id AS prod_routing_id,
                                pr.name AS prod_routing_name,
                                COUNT( DISTINCT CASE WHEN  po.id THEN po.id ELSE NULL END) AS total_prod_order_on_sub_project,
                                COUNT(CASE WHEN wir.status IS NOT NULL THEN wir.status ELSE NULL END) AS total_prod_order_have_wir,
                                COUNT(CASE WHEN SUBSTR(wir.closed_at, 1, 10) <= '$previousDate' AND wir.status IN ('closed', 'N\A')  THEN wir.status ELSE NULL END) AS total_prod_order_have_wir2
                                                        
                                                FROM sub_projects sp
                                                    JOIN prod_orders po ON po.sub_project_id = sp.id
                                                    LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                                                    LEFT JOIN projects pj ON sp.project_id = pj.id
                                                    LEFT JOIN qaqc_wirs wir ON wir.prod_order_id = po.id 
                                                                            AND wir.prod_routing_id = pr.id
                                                                            AND wir.sub_project_id = sp.id
                                                                            AND wir.status IN ('closed', 'N\A')
                                                                            AND SUBSTR(wir.closed_at, 1, 10) <= '$latestDate'
                                                    WHERE 1 = 1";

        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND sp.project_id IN ({$valOfParams['project_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND sp.id IN ({$valOfParams['sub_project_id']})";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND pr.id IN ({$valOfParams['prod_routing_id']})";

                                            $sql .= "   #AND sp.project_id = 8
                                                        #AND sp.id = 107
                                                        #AND po.id = 1325
                                                        #AND pr.id = 49
                                                    GROUP BY #project_id, 
                                                            sub_project_id, 
                                                            prod_routing_id ) AS tb1
                                                LEFT JOIN (
                                                    SELECT
                                                        mtm.term_id AS prod_routing_id,
                                                        COUNT(mtm.doc_id) AS count_wir_description
                                                    FROM many_to_many mtm 
                                                    WHERE 1 = 1 
                                                        AND mtm.doc_type = 'App\\\Models\\\Wir_description' 
                                                        AND mtm.term_type = 'App\\\Models\\\Prod_routing' 
                                                    GROUP BY prod_routing_id
                                                ) AS tb2 ON tb2.prod_routing_id = tb1.prod_routing_id
                                                ORDER BY sub_project_name, prod_routing_name";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            // [
            //     "title" => "Project",
            //     "dataIndex" => "project_id",
            //     "allowClear" => true,
            //     "multiple" => true,
            // ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_id",
                "hasListenTo" => true,
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_id",
                #"hasListenTo" => true,
                "allowClear" => true,
                "multiple" => true,
            ],
        ];
    }

    protected function getTableColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_name',
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_name',
                'width' => 200,
            ],
            [
                'title' => 'Module QTY',
                'dataIndex' => 'total_prod_order_on_sub_project',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'Last month QC Acceptance (%)',
                'dataIndex' => 'previous_acceptance_percent',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'This month QC Acceptance (%)',
                'dataIndex' => 'latest_acceptance_percent',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'sub_project_status',
                'align' => 'center',
            ],

        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['month'] = date("Y-m");
        return $params;
    }

    public function getBasicInfoData($params)
    {
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        return [
            "date_of_update" => $latestDate
        ];
    }
}
