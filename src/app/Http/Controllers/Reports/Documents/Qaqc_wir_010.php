<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use Exception;

class Qaqc_wir_010 extends Report_ParentDocument2Controller
{

    protected $mode = '010';
    protected $viewName = 'document-wir-010';
    protected $tableTrueWidth = true;
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $pageLimit = 100000;


    private function generateCurrentAndPreviousDate($month)
    {
        [$y, $m] = explode('-', $month);
        if ($m === '01') {
            $y = $y - 1;
            $m = 12;
        };
        $previousMonth = str_pad($m - 1, '2', '0', STR_PAD_LEFT);
        $previousDate = $y . "-" . $previousMonth . "-25";

        $latestDate = $month . '-' . "25";
        if ($latestDate > date("Y-m-d")) {
            $latestDate = date("Y-m-d");
            $previousDate = date("Y-m", strtotime($latestDate . " -1 month")) . "-25";
        }
        return [$previousDate, $latestDate];
    }

    private function generateStartAndDayOfWeek($params){
        try{
            $year = $params['year'];
            $weeksData = DateReport::getWeeksInYear($year);
            $indexDates = $weeksData[$params['weeks_of_year']];
            $previousDate = $indexDates['start_date'];
            $latestDate = $indexDates['end_date'];
            return [$previousDate, $latestDate];
        } catch (Exception $e){
            dd($e->getMessage(), $params);
        }
    }

    public function getSqlStr($params)
    {
        // dump($params);
        if ($params['children_mode'] === 'filter_by_year') {
            [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);
        } else {
            [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        }

        $valOfParams = $this->generateValuesFromParamsReport($params);
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
                                pr.prod_routing_id AS prod_routing_id,
                                pr.prod_routing_name AS prod_routing_name,
                                tb_count_order.prod_order_qty AS total_prod_order_on_sub_project,
                                COUNT(CASE WHEN wir.status IS NOT NULL THEN wir.status ELSE NULL END) AS total_prod_order_have_wir,
                                COUNT(CASE WHEN SUBSTR(wir.closed_at, 1, 10) <= '$previousDate' AND wir.status IN ('closed', 'N\A')  THEN wir.status ELSE NULL END) AS total_prod_order_have_wir2
                                                        
                                                FROM sub_projects sp
                                                    JOIN prod_orders po ON po.sub_project_id = sp.id
                                                    LEFT JOIN (
                                                                SELECT 
                                                                    DISTINCT mtm1.term_id AS prod_routing_id,
                                                                    _pr.name AS prod_routing_name
                                                                    FROM (
                                                                        SELECT mtm.term_id
                                                                        FROM many_to_many mtm
                                                                        WHERE mtm.doc_type = 'App\\\Models\\\Wir_description'
                                                                        AND mtm.term_type = 'App\\\Models\\\Prod_routing'
                                                                    ) AS mtm1
                                                                    LEFT JOIN (
                                                                        SELECT mtm.doc_id
                                                                        FROM many_to_many mtm
                                                                        WHERE mtm.doc_type = 'App\\\Models\\\Prod_routing'
                                                                        AND mtm.term_id = 346
                                                                    ) AS mtm2 ON mtm1.term_id = mtm2.doc_id
                                                                    LEFT JOIN prod_routings _pr ON _pr.id = mtm1.term_id
                                                    ) pr ON pr.prod_routing_id = po.prod_routing_id
                                                    LEFT JOIN projects pj ON sp.project_id = pj.id
                                                    LEFT JOIN qaqc_wirs wir ON wir.prod_order_id = po.id 
                                                                            AND wir.prod_routing_id = pr.prod_routing_id
                                                                            AND wir.sub_project_id = sp.id
                                                                            AND wir.status IN ('closed', 'N\A')
                                                                            AND SUBSTR(wir.closed_at, 1, 10) <= '$latestDate'
                                                                            LEFT JOIN (
                                                    	SELECT DISTINCT pr.id AS prod_routing_id, SUM(po.quantity) AS prod_order_qty
                                                              FROM prod_orders po, sub_projects sp, prod_routings pr
                                                                WHERE 1 = 1
                                                                    AND po.sub_project_id = sp.id
                                                                    AND pr.id = po.prod_routing_id
                                                                    AND pr.name != '-- available'";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND pr.id IN ({$valOfParams['prod_routing_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND sp.id IN ({$valOfParams['sub_project_id']})";
        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND sp.project_id IN ({$valOfParams['project_id']})";

        $sql .= "\n GROUP BY prod_routing_id ) tb_count_order ON tb_count_order.prod_routing_id = pr.prod_routing_id
                                                    WHERE 1 = 1";

        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND sp.project_id IN ({$valOfParams['project_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND sp.id IN ({$valOfParams['sub_project_id']})";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND pr.prod_routing_id IN ({$valOfParams['prod_routing_id']})";

        $sql .= "   #AND sp.project_id = 8
                                                        #AND sp.id = 107
                                                        #AND po.id = 1325
                                                        #AND pr.id = 49
                                                        AND pr.prod_routing_name != '-- available'
                                                    GROUP BY #project_id, 
                                                            sub_project_id, 
                                                            prod_routing_id
                                                            ) AS tb1
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
                "firstHidden" => true,
            ],
            [
                'title' => 'Year',
                'dataIndex' => 'year',
                "firstHidden" => true,
            ],
            [
                "title" => "Week",
                "dataIndex" => "weeks_of_year",
                #"allowClear" => true,
                "hasListenTo" => true,
                "firstHidden" => true,
            ],
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

    protected function getTableColumns($params, $dataSource)
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
                'title' => $params['previous_month'] . '<br/>QC Acceptance (%)',
                'dataIndex' => 'previous_acceptance_percent',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => $params['latest_month'] . '<br/>QC Acceptance (%)',
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

    public function getBasicInfoData($params)
    {
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        return [
            'from_date' => $previousDate,
            "date_of_update" => $latestDate
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        // dd($params);
        if (in_array('children_mode',array_keys($params)) && is_null($params['children_mode']) || empty($params)) {
            $params['sub_project_id'] = $this->subProjectId;
            $params['year'] = date('Y');
            $params['children_mode'] = 'filter_by_year';
            $params['month'] = date("Y-m");
    
            if ($params['children_mode'] === 'filter_by_year') {
                $currentWeek = str_pad(date('W'), 2, '0', STR_PAD_LEFT);
                $previousWeek = str_pad(date('W') - 1, 2, '0', STR_PAD_LEFT);
    
                $params['weeks_of_year'] = $currentWeek;
                [$start_date, $end_date] = $this->generateStartAndDayOfWeek($params);
                $params['previous_month'] = 'W' . $previousWeek . '-' . substr(date('Y'), -2) . ' (' . $start_date . ')';
                $params['latest_month'] = 'W' . $currentWeek . '-' . substr(date('Y'), -2) . ' (' . $end_date . ')';
            } else {

                [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
                $params['previous_month'] = substr($previousDate, 0, 7);
                $params['latest_month'] = substr($latestDate, 0, 7);
            }
        }
        if (Report::checkValueOfField($params, 'children_mode')) {
            $settings = CurrentUser::getSettings();
            $indexMode = $params['children_mode'];
            $typeReport = CurrentPathInfo::getTypeReport2($request);
            if(isset($settings[$this->getTable()][$typeReport][$this->mode][$indexMode])){
                // get params in user settings
                $params = $settings[$this->getTable()][$typeReport][$this->mode][$indexMode];
            } else{
                // set default params when don't find params in user settings
                $currentWeek = str_pad(date('W'), 2, '0', STR_PAD_LEFT);
                $params['weeks_of_year'] = $currentWeek;
                $params['month'] = date("Y-m");
                $params['year'] = date('Y');
            }
            if ($params['children_mode'] === 'filter_by_year') {
                $currentWeek = str_pad($params['weeks_of_year'], 2, '0', STR_PAD_LEFT);
                $year = $params['year'];
                [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);
                $params['previous_month'] = 'W'.$currentWeek.'/'.$year.'('. $previousDate.')';
                $params['latest_month'] = 'W'.$currentWeek.'('.$latestDate.')';
            } else {
                [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
                $params['previous_month'] = substr($previousDate, 1, 7);
                $params['latest_month'] = substr($latestDate, 1, 7);
            }
    
        }
        // dump($params);
        return $params;
    }
}
