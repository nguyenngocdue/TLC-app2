<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitFilterProdRoutingShowsOnScreen;
use App\Models\Sub_project;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\ParameterReport;
use App\Utils\Support\Report;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Qaqc_wir_dataSource extends Report_ParentDocument2Controller
{

    protected $tableTrueWidth = true;
    // protected $projectId = 8;
    // protected $subProjectId = 107;
    protected $pageLimit = 100000;

    use TraitFilterProdRoutingShowsOnScreen;

    private function generateCurrentAndPreviousDate($month)
    {
        [$y, $m] = explode('-', $month);
        if ($m === '01' || $m === '1') {
            $y = $y - 1;
            $m = 13;
        };
        $lastDayOfMonth = substr(date("Y-m-t", strtotime("$month-01")), -2);
        $previousMonth = $y . '-' . sprintf('%02d', (int)$m - 1);
        $previousDayOfMonth = substr(date("Y-m-t", strtotime($previousMonth)), -2);
        $previousMonth = str_pad($m - 1, '2', '0', STR_PAD_LEFT);

        $previousDate = $y . "-" . $previousMonth . "-" . $previousDayOfMonth;
        $latestDate = $month . '-' . $lastDayOfMonth;
        return [$previousDate, $latestDate];
    }

    public function getDate($params)
    {
        if ($params['children_mode'] === 'filter_by_week') {
            [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);
        } else {
            [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['year_month']);
        }
        return [$previousDate, $latestDate];
    }

    private function generateStartAndDayOfWeek($params)
    {
        try {
            $year = $params['year'];
            $weeksData = DateReport::getWeeksInYear($year);
            $indexDates = isset($weeksData[$params['weeks_of_year']]) ? $weeksData[$params['weeks_of_year']] : $weeksData[intval($params['weeks_of_year'])];
            $previousDate = $indexDates['start_date'];
            $latestDate = $indexDates['end_date'];
            return [$previousDate, $latestDate];
        } catch (Exception $e) {
            // dd($e->getMessage(), $params);
            return;
        }
    }

    public function getSqlStr($params)
    {
        // reduce items of prod_routing from "Show on screens"
        $idsRoutings = $this->filterProdRoutingByTypeID(346); // QAQC WIR
        $strIdsRoutings = implode(',', $idsRoutings);

        [$previousDate, $latestDate] = $this->getDate($params);

        $valOfParams = $this->generateValuesFromParamsReport($params);

        if ((isset($params['sub_project_id']) && !is_numeric($x = $params['sub_project_id']) && is_null(last($x)))  || !isset($x)
        ) {
            $strIdsSubProjects = ParameterReport::getStringIds('sub_project_id');
            $valOfParams['sub_project_id'] = $strIdsSubProjects;
        }
        // dd($params);
        $sql = " SELECT *
            ,IF(total_prod_order_have_wir_before*100/(prod_order_in_wir*count_wir_description),
                -- Calculate before period
                FORMAT(total_prod_order_have_wir_before*100/(prod_order_in_wir*count_wir_description), 2)

                ,NULL) AS previous_qaqc_percent
            ,IF(total_prod_order_have_wir*100/(prod_order_in_wir*count_wir_description),
                -- Calculate after period
                FORMAT(total_prod_order_have_wir*100/(prod_order_in_wir*count_wir_description),2)
            
                ,NULL) AS latest_qaqc_percent,
            
                
                -- NULL latest_finished_prod_percent,
                -- NULL previous_finished_prod_percent,
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
                        JOIN prod_orders po ON po.sub_project_id = sp.id AND sp.id IS NOT NULL
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
                                                AND wir.sub_project_id = sp.id
                                                AND sp.id IS NOT NULL";

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
                                                                    AND po.sub_project_id = sp.id AND po.id IS NOT NULL
                                                                    AND pr.id = po.prod_routing_id
                                                                    AND pr.name != '-- available'
                                                                    AND sp.id IS NOT NULL
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
                                                WHERE 1 = 1
                                                AND count_prod_order_have_wir.sub_project_id IS NOT NULL
                                                ORDER BY sub_project_name, prod_routing_name";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
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
                "showNow" => false,
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
        $typeDate = isset($params['children_mode']) && $params['children_mode'] === "filter_by_month" ?
            "Month" : 'Week';
        return [
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_name',
                'width' => 180,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_name',
                'width' => 300,
            ],
            [
                'title' => 'QTY',
                'dataIndex' => 'number_of_prod_orders',
                'align' => 'right',
                'width' => 150,
            ],
            [
                'title' => 'Apartment Q.ty',
                'dataIndex' => 'total_prod_order',
                'align' => 'right',
                'width' => 150,
            ],
            [
                'title' => 'Last ' . $typeDate . ' Production Completion (%) </br>(' . Arr::get($params, 'previous_month', '') . ')',
                'dataIndex' => 'previous_finished_prod_percent',
                'width' => 150,
                'align' => 'right',
            ],
            [
                'title' => 'Last ' . $typeDate . ' QC Acceptance (%) </br>(' . Arr::get($params, 'previous_month', '') . ')',
                'dataIndex' => 'previous_qaqc_percent',
                'align' => 'right',
                'width' => 150,
                'align' => 'right',
            ],
            [
                'title' => 'This ' . $typeDate . ' Production Completion (%) </br>(' . Arr::get($params, 'latest_month', '') . ')',
                'dataIndex' => 'latest_finished_prod_percent',
                'align' => 'right',
                'width' => 150,
                'align' => 'right',
            ],
            [
                'title' => 'This ' . $typeDate . ' QC Acceptance (%)</br>(' . Arr::get($params, 'latest_month', '') . ')',
                'dataIndex' => 'latest_qaqc_percent',
                'align' => 'right',
                'width' => 150,
                'align' => 'right',
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'percent_status',
                'align' => 'center',
            ],
        ];
    }

    public function getBasicInfoData($params)
    {
        // dd($params);
        $params['month'] = $params['year'] . '-' . str_pad($params['only_month'], 2, '0', STR_PAD_LEFT);
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        return [
            'from_date' => $previousDate,
            "date_of_update" => $latestDate
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        if (!$this->hasChildrenMode($params) || empty($params) || isset($params['children_mode'])) {
            $params = $this->getDefaultParamsForFilterByMonth($params, $request);
        } elseif (Report::checkValueOfField($params, 'children_mode')) {
            $params = $this->getParamsFromUserSettings($params, $request);
        }
        return $params;
    }

    protected function hasChildrenMode($params)
    {
        return isset($params['children_mode']) && !is_null($params['children_mode']);
    }


    protected function getDefaultParamsForFilterByMonth($params, $request)
    {
        if ($params && isset($params['children_mode'])) {
            $settings = CurrentUser::getSettings();
            $indexMode = $params['children_mode'];
            $typeReport = CurrentPathInfo::getTypeReport2($request);
            if (isset($settings[$this->getTable()][$typeReport][$this->mode][$indexMode])) {
                $params = $settings[$this->getTable()][$typeReport][$this->mode][$indexMode];
                if (isset($settings[$this->getTable()][$typeReport][$this->mode]['optionPrintLayout'])) {
                    $params['optionPrintLayout'] = $settings[$this->getTable()][$typeReport][$this->mode]['optionPrintLayout'];
                }
            }
        }
        $params['sub_project_id'] = isset($params['sub_project_id']) ? $params['sub_project_id'] : null;
        $params['year'] = isset($params['year'])  ? $params['year'] : date('Y');
        $params['children_mode'] = isset($params['children_mode']) && $params['children_mode'] ? $params['children_mode'] : 'filter_by_month';
        $params['only_month'] = isset($params['only_month']) ? $params['only_month'] : (string)intval(date("m"));
        $params['year_month'] = isset($params['year_month']) ? $params['year_month'] : $params['year'] . '-' . sprintf('%02d', $params['only_month']);

        if ($params['children_mode'] === 'filter_by_week') {
            $currentWeek = str_pad(date('W'), 2, '0', STR_PAD_LEFT);
            $previousWeek = str_pad(date('W') - 1, 2, '0', STR_PAD_LEFT);

            $params['weeks_of_year'] = $currentWeek;
            [$start_date, $end_date] = $this->generateStartAndDayOfWeek($params);
            $params['previous_month'] = 'W' . $previousWeek . '-' . substr(date('Y'), -2) . ' (' . $start_date . ')';
            $params['latest_month'] = 'W' . $currentWeek . '-' . substr(date('Y'), -2) . ' (' . $end_date . ')';
        } else {
            [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['year_month']);
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
            $params = $this->getDefaultParamsForFilterByMonth($params, $request);
        }
        // update prams into user setting
        if ($params['children_mode'] === 'filter_by_week') {
            $params = $this->updateParamsForFilterByWeek($params);
        }
        if ($params['children_mode'] === 'filter_by_month') {
            $params = $this->updateParamsForMonths($params);
        }
        return $params;
    }

    protected function updateParamsForFilterByWeek($params)
    {
        $currentWeek = str_pad($params['weeks_of_year'], 2, '0', STR_PAD_LEFT);
        $year = $params['year'];
        [$previousDate, $latestDate] = $this->generateStartAndDayOfWeek($params);

        $previousM = intval($currentWeek) === 1 ? 12 : str_pad($currentWeek - 1, 2, '0', STR_PAD_LEFT);
        $year = $previousM === 12 ? $year - 1 : $year;

        $params['previous_month'] = 'W' . ($previousM) . '/' . $year . '(' . $previousDate . ')';
        $params['latest_month'] = 'W' . $currentWeek . '(' . $latestDate . ')';

        return $params;
    }

    protected function updateParamsForMonths($params)
    {
        $params['month'] = $params['year'] . '-' . str_pad($params['only_month'], 2, '0', STR_PAD_LEFT);
        [$previousDate, $latestDate] = $this->generateCurrentAndPreviousDate($params['month']);
        $params['previous_month'] = substr($previousDate, 0, 7);
        $params['latest_month'] = substr($latestDate, 0, 7);
        return $params;
    }


    public function changeDataSource($dataSource, $params)
    {
        // set status for each routing by "latest_qaqc_percent"
        foreach ($dataSource as $values) {
            $value = (int)$values->latest_qaqc_percent;
            if ($value === 100) {
                $values->percent_status = 'Finished';
            } elseif ($value > 0 and $value < 100) {
                $values->percent_status = 'In Progress';
            } else {
                $values->percent_status = 'Not Yet';
            }
        }
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function getApartmentsEachProdRouting($params)
    {
        $paramsFormat = ParameterReport::formatValueParams($params);
        $sqlStr = "SELECT
                        sp.id AS sub_project_id,
                        pr.id AS prod_routing_id,
                        COUNT(DISTINCT pjun.id) AS number_of_pj_units
                        FROM sub_projects sp
                        LEFT JOIN prod_orders po ON po.sub_project_id = sp.id 
                        LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id  
                        LEFT JOIN pj_modules pjmo ON pjmo.pj_unit_id = po.meta_id
                        LEFT JOIN pj_units pjun ON pjun.id = pjmo.pj_unit_id
                        WHERE 1 = 1
                        AND pjmo.id IS NOT NULL
                        AND pr.id IS NOT NULL";
        if (Report::checkValueOfField($paramsFormat, 'prod_routing_id')) $sqlStr .= "\n AND pr.id IN ({$paramsFormat['prod_routing_id']})";
        if (Report::checkValueOfField($paramsFormat, 'sub_project_id')) $sqlStr .= "\n AND sp.id IN ({$paramsFormat['sub_project_id']})";
        $sqlStr .= "\n GROUP BY sp.id, pr.id";
        $sqlData = DB::select($sqlStr);
        return $sqlData;
    }
}
