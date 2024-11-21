<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;

class Prod_sequence_040 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitForwardModeReport;

    protected $mode = '040';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $tableTrueWidth = true;
    protected $maxH = 30 * 16;
    protected $typeView = 'report-pivot';
    protected $type = 'prod_sequence';
    protected $pageLimit = 10;

    // DataSource
    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);

        // dd($valOfParams, $params);
        $sql = "SELECT 
                        DATE_FORMAT(SUBSTR(po.started_at, 1, 10), '%d/%m/%Y') AS started_at_prod_order,
                        DATE_FORMAT(SUBSTR(po.finished_at, 1, 10), '%d/%m/%Y') AS finished_at_prod_order,
                        sp.project_id AS project_id
                        ,pj.name AS project_name
                        ,sp.id AS sub_project_id
                        ,sp.name AS sub_project_name
                        ,po.id AS prod_order_id
                        ,po.name AS prod_order_name
                        ,po.production_name AS production_name
                        ,po.quantity AS prod_order_quantity
                        ,pr.id AS prod_routing_id    
                        ,pr.name AS prod_routing_name    
                        ,po.status AS prod_order_status
                        ,po.total_calendar_days AS total_calendar_days
                        ,po.total_days_no_sun_no_ph AS independent_holiday_sunday_day
                        ,FORMAT(po.total_hours/8,2) AS net_working_day
                        ,po.total_days_have_ts AS total_days_have_ts
                        ,po.total_discrepancy_days AS total_discrepancy_days";
        $sql .= "\n FROM sub_projects sp
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                    LEFT JOIN projects pj ON sp.project_id = pj.id
                    WHERE 1 = 1";
        if ($pj = $valOfParams['project_id']) $sql .= "\n AND sp.project_id = $pj";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND po.sub_project_id = $sub";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";
        if ($prodOrder = $valOfParams['prod_order_id']) $sql .= "\n AND po.id IN ($prodOrder)";
        if ($status = $valOfParams['status']) $sql .= "\n AND po.status IN( $status )";
        elseif (!isset($params['status'])) $sql .= "\n AND po.status IN ('in_progress', 'finished', 'on_hold')";
        $sql .= "\n ORDER BY sub_project_name, prod_order_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        $params['prod_routing_id'] = $this->prodRoutingId;
        $params['page_limit'] = $this->pageLimit;
        return $params;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'hasListenTo' => true,
            ],
            [
                'title' => 'Production Routing',
                'dataIndex' => 'prod_routing_id',
                'hasListenTo' => true,
                'allowClear' => true,
            ],
            [
                'title' => 'Production Order',
                'dataIndex' => 'prod_order_id',
                'hasListenTo' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Status',
                'dataIndex' => 'status',
                'allowClear' => true,
                'multiple' => true,
            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {

        $stringIcon = "class='text-base fa-duotone fa-circle-question hover:bg-blue-400 rounded'></i>";
        $notes = [
            'total_calendar_days' => "<br/><i title='Total Calender Days is calculated by counting the days from the start date to the finish date of a production order (note: please check the column \"Status of Production Order\" to know whether the production order is finished or not).'" . $stringIcon,
            'independent_holiday_sunday_day' => "<br/><i title='Working Days is calculated by counting the normal working days (by deducting Sundays and Public Holidays from Total Calender Days).'" . $stringIcon,
            'total_days_have_ts' => "<br/><i title='Working Days without Downtime Days is calculated by counting the normal working days without downtime days (by Column Working Days deducts the number of days with no production activity of the production order).'" . $stringIcon,
            'net_working_day' => "<br/><i title='Net Working Days is the number of days which is calculated based on the actual working hours of the production order.'" . $stringIcon,
            'total_discrepancy_days' => "<br/><i title='Downtime Days is the number of days with no production activity for the production order (Column \"Working Days\" deducts Column \"Working Days without Downtime\").'" . $stringIcon,
        ];

        return
            [
                [
                    "title" => "Start",
                    "dataIndex" => "started_at_prod_order",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Finish",
                    "dataIndex" => "finished_at_prod_order",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Project",
                    "dataIndex" => "project_name",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" => 120,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Routing",
                    "dataIndex" => "prod_routing_name",
                    "align" => "left",
                    "width" => 250,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "left",
                    "width" => 250,
                    'fixed' => 'left',

                ],
                [
                    "title" => "Production Order Name",
                    "dataIndex" => "production_name",
                    "align" => "left",
                    "width" => 250,
                ],
                [
                    "title" => "Status",
                    "dataIndex" => "prod_order_status",
                    "align" => "center",
                    "width" => 150,
                ],

                [
                    "title" => "Quantity",
                    "dataIndex" => "prod_order_quantity",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Total Calendar Days {$notes['total_calendar_days']}",
                    "dataIndex" => "total_calendar_days",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Working Days {$notes['independent_holiday_sunday_day']}",
                    "dataIndex" => "independent_holiday_sunday_day",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Working Days without Downtime {$notes['total_days_have_ts']}",
                    "dataIndex" => "total_days_have_ts",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                // [
                //     "title" => "Net Working Days {$notes['net_working_day']}",
                //     "dataIndex" => "net_working_day",
                //     "align" => "right",
                //     "width" => 150,
                //     "footer" => "agg_sum"
                // ],
                [
                    "title" => "Downtime Days  {$notes['total_discrepancy_days']}",
                    "dataIndex" => "total_discrepancy_days",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],

            ];
    }

    public function changeDataSource($dataSource, $params)
    {
        $dataSource = Report::getItemsFromDataSource($dataSource);
        $route = route('report-prod_sequence_050');
        foreach ($dataSource  as $key => &$items) {
            if ($items->prod_order_status !== 'closed') {
                $items->finished_at_prod_order = (object)[
                    'value' => $items->finished_at_prod_order,
                    'cell_class' => 'text-gray-300'
                ];
            }
            if (isset($items->independent_holiday_sunday_day)) {
                $projectId = $items->project_id;
                $subProjectId = $items->sub_project_id;
                $prodOrderId = $items->prod_order_id;
                $prodRoutingId = $items->prod_routing_id;
                $route = $route . '?'
                    . 'project_id=' . $projectId
                    . '&sub_project_id=' . $subProjectId
                    . '&prod_order_id=' . $prodOrderId
                    . '&prod_routing_id=' . $prodRoutingId;
                $items->independent_holiday_sunday_day = (object)[
                    'value' => $items->independent_holiday_sunday_day,
                    'cell_class' => 'text-blue-700',
                    'cell_href' => $route,
                ];
            }
        }
        return collect($dataSource);
    }
}
