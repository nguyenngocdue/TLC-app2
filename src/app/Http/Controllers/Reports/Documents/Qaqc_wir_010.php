<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitFilterProdRoutingShowsOnScreen;
use App\Models\Sub_project;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\ParameterReport;
use App\Utils\Support\Report;
use Exception;
use Illuminate\Support\Arr;

class Qaqc_wir_010 extends Report_ParentDocument2Controller
{

    protected $mode = '010';
    protected $viewName = 'document-wir-010';
    protected $tableTrueWidth = true;
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $pageLimit = 100000;

    use TraitFilterProdRoutingShowsOnScreen;


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

    private function generateStartAndDayOfWeek($params)
    {
        try {
            $year = $params['year'];
            $weeksData = DateReport::getWeeksInYear($year);
            $indexDates = $weeksData[$params['weeks_of_year']];
            $previousDate = $indexDates['start_date'];
            $latestDate = $indexDates['end_date'];
            return [$previousDate, $latestDate];
        } catch (Exception $e) {
            dd($e->getMessage(), $params);
        }
    }

    public function getSqlStr($params)
    {
        // reduce items of prod_routing from "Show on screen"
        $idsRoutings = $this->filterProdRoutingByTypeID(346); // QAQC WIR
        $strIdsRoutings = implode(',', $idsRoutings);


        if ($params['children_mode'] === 'filter_by_week') {
            [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);
        } else {
            [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        }
        $valOfParams = $this->generateValuesFromParamsReport($params);

        if((isset($params['sub_project_id']) && !is_numeric($x = $params['sub_project_id']) && is_null(last($x)))  || !isset($x)
        ){
            $strIdsSubProjects = ParameterReport::getStringIds('sub_project_id');
            $valOfParams['sub_project_id'] = $strIdsSubProjects;
        }


        $sql = " SELECT *
                            ,IF(total_prod_order_have_wir*100/(prod_order_in_wir*count_wir_description),
                                -- Calculate after period
                                FORMAT(total_prod_order_have_wir*100/(prod_order_in_wir*count_wir_description),2)
                            
                                ,NULL) AS latest_acceptance_percent
                                
                            ,IF(total_prod_order_have_wir_before*100/(prod_order_in_wir*count_wir_description),
                                -- Calculate before period
                                FORMAT(total_prod_order_have_wir_before*100/(prod_order_in_wir*count_wir_description), 2)

                                ,NULL) AS previous_acceptance_percent,
                                count_wir_description,
                                total_prod_order_have_wir_before,
                                total_prod_order_have_wir,
                                total_prod_order_on_sub_project,
                                prod_order_in_wir,
                                total_prod_order_have_wir
                            FROM (SELECT
                                sp.project_id AS project_id,
                                sp.status AS sub_project_status,
                                pj.name AS project_name,
                                sp.id AS sub_project_id,
                                sp.name AS sub_project_name,
                                pr.prod_routing_id AS prod_routing_id,
                                pr.prod_routing_name AS prod_routing_name,
                                tb_count_order.prod_order_qty AS total_prod_order_on_sub_project,
                                tb_count_order.prod_order_in_wir AS prod_order_in_wir
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
                                                        LEFT JOIN prod_routings _pr ON _pr.id = mtm1.term_id AND _pr.id IN ( $strIdsRoutings )
                                        ) pr ON pr.prod_routing_id = po.prod_routing_id
                                        LEFT JOIN projects pj ON sp.project_id = pj.id
                                        LEFT JOIN qaqc_wirs wir ON wir.prod_order_id = po.id AND wir.deleted_by IS NULL
                                                                AND wir.deleted_by IS NULL
                                                                AND wir.prod_routing_id = pr.prod_routing_id
                                                                AND wir.sub_project_id = sp.id";

        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND wir.prod_routing_id IN ({$valOfParams['prod_routing_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND wir.sub_project_id IN ({$valOfParams['sub_project_id']})";
                                                                            
        $sql .= "\n AND SUBSTR(wir.closed_at, 1, 10) <= '$latestDate' OR wir.closed_at IS NULL
                                                                          	AND (
                                                                               (wir.status IN ('closed', 'not_applicable'))
                                                                               AND wir.status NOT IN ('rejected', 'assigned', 'new')
                                                                            )

                                                                            LEFT JOIN (
                                                    	SELECT DISTINCT  sp.project_id AS project_id, pr.id AS prod_routing_id, SUM(po.quantity) AS prod_order_qty, COUNT(po.id) AS prod_order_in_wir
                                                              FROM prod_orders po, sub_projects sp, prod_routings pr
                                                                WHERE 1 = 1
                                                                    AND pr.id IN ( $strIdsRoutings)
                                                                    AND po.sub_project_id = sp.id
                                                                    AND pr.id = po.prod_routing_id
                                                                    AND pr.name != '-- available'
                                                                    ";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND pr.id IN ({$valOfParams['prod_routing_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND sp.id IN ({$valOfParams['sub_project_id']})";
        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND sp.project_id IN ({$valOfParams['project_id']})";

        $sql .= "\n GROUP BY prod_routing_id, project_id) tb_count_order ON tb_count_order.prod_routing_id = pr.prod_routing_id AND tb_count_order.project_id = sp.project_id
                                                    WHERE 1 = 1";

        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND sp.project_id IN ({$valOfParams['project_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND sp.id IN ({$valOfParams['sub_project_id']})";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND pr.prod_routing_id IN ({$valOfParams['prod_routing_id']})";

                                            $sql .= "\n AND pr.prod_routing_name != '-- available'
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

                                                LEFT JOIN (SELECT
                                                wir.project_id  AS project_id,
												wir.sub_project_id  AS sub_project_id,
        										wir.prod_routing_id AS wir_prod_routing_id ,
                                               COUNT(CASE WHEN
                                                 (SUBSTR(wir.closed_at, 1, 10) <= '$latestDate' OR wir.closed_at IS NULL)
                                                 AND wir.status IN ('closed', 'not_applicable')
                                                 AND wir.status NOT IN ('rejected', 'assigned', 'new')
                                               THEN wir.status ELSE NULL END) AS total_prod_order_have_wir,

                                               COUNT(CASE WHEN
                                               (SUBSTR(wir.closed_at, 1, 10) <= '$previousDate' OR wir.closed_at IS NULL)
                                               AND wir.status IN ('closed', 'not_applicable')
                                               AND wir.status NOT IN ('rejected', 'assigned', 'new')
                                             THEN wir.status ELSE NULL END) AS total_prod_order_have_wir_before

                                             FROM qaqc_wirs wir
                                             WHERE 1 = 1 
                                                    AND wir.deleted_by IS NULL";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND wir.prod_routing_id IN ({$valOfParams['prod_routing_id']})";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND wir.sub_project_id IN ({$valOfParams['sub_project_id']})";
                                                 $sql .= "\n GROUP BY wir_prod_routing_id,project_id, sub_project_id
                                                ) AS count_prod_order_have_wir 
                                                ON tb1.prod_routing_id = count_prod_order_have_wir.wir_prod_routing_id AND tb1.sub_project_id = count_prod_order_have_wir.sub_project_id
                                                ORDER BY sub_project_name, prod_routing_name";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
           /*  [
                'title' => 'Month',
                'dataIndex' => 'month',
                "firstHidden" => true,
            ], */
            [
                'title' => 'Year',
                'dataIndex' => 'year',
                "firstHidden" => true,
            ],
            [
                'title' => 'Month',
                'dataIndex' => 'only_month',
                'showNumber' => true,
                "firstHidden" => true,
                "showNow" => true,
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
                'title' =>  Arr::get($params, 'previous_month', '') . '<br/>QC Acceptance (%)',
                'dataIndex' => 'previous_acceptance_percent',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => Arr::get($params, 'latest_month', '') . '<br/>QC Acceptance (%)',
                'dataIndex' => 'latest_acceptance_percent',
                'align' => 'right',
                'width' => 180,
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'percent_status',
                'align' => 'center',
            ],
            // [
            //     'dataIndex' => 'count_wir_description'
            // ],
            // [
            //     'dataIndex' => 'total_prod_order_have_wir_before'
            // ],
            // [
            //     'dataIndex' => 'total_prod_order_have_wir'
            // ],
            // [
            //     'dataIndex' => 'total_prod_order_on_sub_project'
            // ],
            // [
            //     'dataIndex' => 'prod_order_in_wir'
            // ]
        ];
    }

    public function getBasicInfoData($params)
    {
        $params['month'] = $params['year'].'-'. str_pad($params['only_month'], 2, '0', STR_PAD_LEFT);
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        return [
            'from_date' => $previousDate,
            "date_of_update" => $latestDate
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        if (!$this->hasChildrenMode($params) || empty($params)) {
            $params = $this->getDefaultParamsForFilterByMonth();
        } elseif (Report::checkValueOfField($params, 'children_mode')) {
            $params = $this->getParamsFromUserSettings($params, $request);
        }
            // dd($params);
        return $params;
    }

    protected function hasChildrenMode($params)
    {
        return isset($params['children_mode']) && !is_null($params['children_mode']);
    }
    

    protected function getDefaultParamsForFilterByMonth()
    {
        $params['sub_project_id'] = $this->subProjectId;
        $params['year'] = date('Y');
        $params['children_mode'] = 'filter_by_week';
        $params['month'] = date("Y-m");
        $params['only_month'] = date("m");

        if ($params['children_mode'] === 'filter_by_week') {
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
        return $params;
    }

    protected function getParamsFromUserSettings($params, $request)
    {
        $settings = CurrentUser::getSettings();
        $indexMode = $params['children_mode'];
        $typeReport = CurrentPathInfo::getTypeReport2($request);

        // get param when user has already submitted
        if (isset($settings[$this->getTable()][$typeReport][$this->mode][$indexMode])) {
            $params = $settings[$this->getTable()][$typeReport][$this->mode][$indexMode];
        } else {
            //Set default when params is empty
            $params = $this->getDefaultParamsForFilterByMonth();
        }
        // update prams into user setting
        if ($params['children_mode'] === 'filter_by_week') {
            $params = $this->updateParamsForFilterByWeek($params);
        } else {
            $params = $this->updateParamsForMonths($params);
        }
        // dump($params);
        return $params;
    }

    protected function updateParamsForFilterByWeek($params)
    {
        $currentWeek = str_pad($params['weeks_of_year'], 2, '0', STR_PAD_LEFT);
        $year = $params['year'];
        [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);
        $params['previous_month'] = 'W' . ($currentWeek - 1) . '/' . $year . '(' . $previousDate . ')';
        $params['latest_month'] = 'W' . $currentWeek . '(' . $latestDate . ')';

        return $params;
    }

    protected function updateParamsForMonths($params)
    {
        $params['month'] = $params['year'].'-'. str_pad($params['only_month'], 2, '0', STR_PAD_LEFT);
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        $params['previous_month'] = substr($previousDate, 0, 7);
        $params['latest_month'] = substr($latestDate, 0, 7);

        return $params;
    }
    

    public function changeDataSource($dataSource, $params){
        // set status for each routing by "latest_acceptance_percent"
        foreach ($dataSource as $values){
            $value = (int)$values->latest_acceptance_percent;
                if ($value === 100){
                    $values->percent_status = 'Finished';
                } elseif($value > 0 and $value < 100) {
                    $values->percent_status = 'In Progress';
                }else {
                    $values->percent_status = 'Not Yet';
                }
        }
        // dd($dataSource);
        return collect($dataSource);
    }
}
