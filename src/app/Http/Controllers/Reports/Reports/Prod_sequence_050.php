<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\Report;
use Illuminate\Support\Collection;

class Prod_sequence_050 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitForwardModeReport;

    protected $mode = '050';
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 49;
    protected $tableTrueWidth = true;
    protected $maxH = 30;
    protected $typeView = 'report-pivot';
    protected $type = 'prod_sequence';
    protected $pageLimit = 10;

    // DataSource
    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        $sql = "SELECT
                    sp.project_id AS project_id
                    ,pj.name AS project_name
                    ,sp.id AS sub_project_id
                    ,sp.name AS sub_project_name
                    ,prl.id AS prod_routing_link_id
                    ,prl.name AS prod_routing_link_name
                    ,pr.id AS prod_routing_id
                    ,pr.name AS prod_routing_name
                    ,pd.id AS prod_discipline_id
                    ,pd.name AS prod_discipline_name
                    ,po.name AS prod_order_name
                    ,po.production_name AS prod_name
                    ,po.id AS prod_order_id
                    ,po.status AS prod_order_status
                    ,prd.order_no AS order_no


                    ,IF(pose.total_calendar_days, pose.total_calendar_days, NULL) AS total_calendar_days
                    ,IF(pose.total_days_no_sun_no_ph, pose.total_days_no_sun_no_ph, NULL) AS independent_holiday_sunday_day
                    ,IF(pose.total_hours, FORMAT(pose.total_hours/8,2), NULL) AS net_working_day
                    ,IF(pose.total_days_have_ts, pose.total_days_have_ts, NULL) AS total_days_have_ts
                    ,pose.total_discrepancy_days AS total_discrepancy_days
                    ,pose.status AS prod_sequence_status

                    #,SUM(po.total_calendar_days) AS total_calendar_days
                    ,FORMAT(IF(SUM(pose.total_hours),pose.total_hours, NULL) ,2) AS sum_total_hours
                    ,FORMAT(IF(AVG(pose.worker_number),AVG(pose.worker_number), NULL),2) AS avg_man_power
                    ,FORMAT(IF(SUM(pose.total_hours)*AVG(pose.worker_number), SUM(pose.total_hours)*AVG(pose.worker_number), NULL),2) AS hours
                    ,SUM(DATEDIFF(pose.end_date, pose.start_date) + 1) AS number_of_days_prod_routing_link
                    ,DATE_FORMAT(SUBSTR(MIN(pose.start_date), 1, 10), '%d/%m/%Y') AS from_date
                    ,DATE_FORMAT(SUBSTR(MAX(pose.end_date), 1, 10), '%d/%m/%Y') AS to_date
                    FROM sub_projects sp
                        JOIN prod_orders po ON po.sub_project_id = sp.id
                        LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                        LEFT JOIN projects pj ON sp.project_id = pj.id
                        LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                        LEFT JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                        LEFT JOIN prod_routing_details prd ON prl.id = prd.prod_routing_link_id AND prd.prod_routing_id = pr.id
                        JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                        WHERE 1 = 1";
        if ($pj = $valOfParams['project_id']) $sql .= "\n AND sp.project_id = $pj";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND po.sub_project_id =  $sub";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id IN ($pr)";
        if ($prl = $valOfParams['prod_routing_link_id']) $sql .= "\n AND prl.id IN ($prl)";
        if ($po = $valOfParams['prod_order_id']) $sql .= "\n AND po.id IN ($po)";
        
        // if($status = $valOfParams['status']) $sql .= "\n AND po.status IN( $status )";
                            $sql .="\n AND pose.deleted_by IS NULL
                        GROUP BY project_id, sub_project_id,prod_routing_link_id,prod_routing_id,po.id, po.name,po.production_name
                        ORDER BY project_name, sub_project_name, prod_routing_name, prod_discipline_name, order_no, prod_order_name";
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
                'title' => 'Sub-Project',
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
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
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
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'hasListenTo' => true,
                'multiple' => true,
            ]
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
                    "title" => "From Date",
                    "dataIndex" => "from_date",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left'
                ],
                [
                    "title" => "To Date",
                    "dataIndex" => "to_date",
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
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => 250,
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "left",
                    "width" => 250,
                ],
                [
                    "title" => "Production Order Name",
                    "dataIndex" => "prod_name",
                    "align" => "left",
                    "width" => 250,
                ],
                [
                    "title" => "Status <br/>(Production Order)",
                    "dataIndex" => "prod_order_status",
                    "align" => "center",
                    "width" => 150,
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 400,
                ],
                [
                    "title" => "Status <br/>(Production Routing Link)",
                    "dataIndex" => "prod_sequence_status",
                    "align" => "center",
                    "width" => 150,
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
                [
                    "title" => "Net Working Days {$notes['net_working_day']}",
                    "dataIndex" => "net_working_day",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Downtime Days  {$notes['total_discrepancy_days']}",
                    "dataIndex" => "total_discrepancy_days",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Total Hours",
                    "dataIndex" => "sum_total_hours",
                    "align" => "right",
                    "width" => 120,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Man Power",
                    "dataIndex" => "avg_man_power",
                    "align" => "right",
                    "width" => 120,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Total Man Hours",
                    "dataIndex" => "hours",
                    "align" => "right",
                    "width" => 120,
                    "footer" => "agg_sum"
                ]
              
            ];
    }
    // public function changeDataSource($dataSource, $params)
    // {
    //     $dataSource = Report::getItemsFromDataSource($dataSource);
    //     dd($dataSource);
    // }
}
