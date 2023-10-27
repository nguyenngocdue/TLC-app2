<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DateReport;

class Prod_sequence_060 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitForwardModeReport;

    protected $mode = '060';
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
                    ,SUM(po.total_calendar_days) AS total_calendar_days
                    ,SUM(po.total_days_no_sun_no_ph) AS independent_holiday_sunday_day
                    ,FORMAT(IF(SUM(pose.total_hours),SUM(pose.total_hours), NULL) ,2) AS sum_total_hours
                    ,FORMAT(IF(AVG(pose.worker_number),AVG(pose.worker_number), NULL),2) AS avg_man_power
                    ,FORMAT(IF(SUM(pose.total_hours)*AVG(pose.worker_number), SUM(pose.total_hours)*AVG(pose.worker_number), NULL),2) AS hours
                    ,SUM(DATEDIFF(pose.end_date, pose.start_date)) AS number_of_days_prod_routing_link
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
        
        // if($status = $valOfParams['status']) $sql .= "\n AND po.status IN( $status )";
                            $sql .="\n AND pose.deleted_by IS NULL
                        GROUP BY project_id, sub_project_id,prod_routing_link_id,prod_routing_id";
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
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'hasListenTo' => true,
                'multiple' => true,
            ],
            // [
            //     'title' => 'Status',
            //     'dataIndex' => 'status',
            //     'allowClear' => true,
            //     'multiple' => true,
            // ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
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
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 400,
                ],
                [
                    "title" => "Total Days",
                    "dataIndex" => "number_of_days_prod_routing_link",
                    "align" => "right",
                    "width" => 140,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Working Days",
                    "dataIndex" => "independent_holiday_sunday_day",
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
                    "title" => "Hours",
                    "dataIndex" => "hours",
                    "align" => "right",
                    "width" => 120,
                    "footer" => "agg_sum"
                ],
              
            ];
    }
}
