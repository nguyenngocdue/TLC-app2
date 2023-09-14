<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\Report;


class Prod_sequence_030 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitForwardModeReport;

    protected $mode = '030';
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;
    protected $tableTrueWidth = true;
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $type = 'prod_sequence';
    protected $pageLimit = 100;

    // DataSource
    public function getSqlStr($params)
    {
        $sql = "SELECT 
                        sp.project_id AS project_id
                        ,sp.id AS sub_project_id
                        ,sp.name AS sub_project_name
                        ,po.id AS prod_order_id
                        ,po.name AS prod_order_name
                        ,prl.id AS prod_routing_link_id
                        ,prl.name AS prod_routing_link_name
                        ,pr.id AS prod_routing_id    
                        ,pr.name AS prod_routing_name    
                        ,po.status AS pro_status
                        #,pose.total_hours AS total_hours
                        ,prd.target_hours AS target_hours
                        ,prd.target_man_hours AS target_man_hours
                        ,prd.target_man_power AS target_man_power
                        ,FORMAT(pose.total_man_hours,2) AS total_man_hours
                        ,pd.id AS prod_discipline_id
                        ,pd.name AS prod_discipline_name

                        ,FORMAT(ROUND((pru.worker_number), 2),2) AS man_power
                        ,FORMAT(ROUND(SUM((TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60)),2),2) AS total_hours
                        ,FORMAT(ROUND(pru.worker_number * SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)),2),2) AS man_hours

                    FROM sub_projects sp
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id


                    LEFT JOIN prod_sequences pose ON pose.prod_order_id = po.id
                    LEFT JOIN prod_routing_links prl ON prl.id = pose.prod_routing_link_id
                    LEFT JOIN prod_routing_details prd ON prl.id = prd.prod_routing_link_id AND prd.prod_routing_id = pr.id

                    JOIN prod_disciplines pd ON prl.prod_discipline_id = pd.id
                    JOIN prod_runs pru ON pru.prod_sequence_id = pose.id

                    WHERE 1 = 1
                        AND sp.project_id = '{{project_id}}'
                        AND sp.id = {{sub_project_id}}";
        if (isset($params['prod_order_id'])) $sql .= "\n AND po.id = {{prod_order_id}}";
        if (isset($params['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
                        $sql .= "\n AND pose.status IN ('in_progress', 'finished', 'on_hold')
                        AND po.status IN ('in_progress', 'finished', 'on_hold')
                        AND SUBSTR(pru.date, 1, 10) <= '{{picker_date}}'
                        AND pose.deleted_by IS NULL";

        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND prl.id = {{prod_routing_link_id}}";
        $sql .= "\n GROUP BY project_id, sub_project_name, prod_order_id, prod_order_name, sub_project_id,prod_routing_link_id,pru.worker_number
                    ,target_hours
                    ,target_man_hours
                    ,target_man_power
                    ,total_man_hours
                    ORDER BY sub_project_name, prod_order_name;";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $a = 'picker_date';
        $b = 'project_id';
        $c = 'sub_project_id';
        $d = 'prod_routing_id';
        $e = 'page_limit';
        if (Report::isNullParams($params)) {
            $params[$a] = date('d/m/Y');
            $params[$b] = $this->projectId;
            $params[$c] = $this->subProjectId;
            $params[$d] = $this->prodRoutingId;
            $params[$e] = $this->pageLimit;
        }
        return $params;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'singleDatePicker' => true,
            ],
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
                'title' => 'Production Discipline',
                'dataIndex' => 'prod_discipline_id',
                'allowClear' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'multiple' => true,
                'hasListenTo' => true,

            ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        return
            [
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" => 100,
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "left",
                    "width" => 100,
                ],
                [
                    "title" => "Production Routing",
                    "dataIndex" => "prod_routing_name",
                    "align" => "left",
                    "width" => 250,
                ],
                [
                    "title" => "Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => 200,
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 600,
                ],
                [
                    "dataIndex" => "target_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "dataIndex" => "target_man_hours", // filtering from static number in database
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum'
                ],
                [
                    "dataIndex" => "target_man_power",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum'
                ],
                [
                    "dataIndex" => "total_man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "title" => "Total Hours (Actual)", // for a prod_order
                    "dataIndex" => "total_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "title" => "Man Power (Actual)",
                    "dataIndex" => "man_power",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "title" => "Man Hours (Actual)",
                    "dataIndex" => "man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
            ];
    }
}
