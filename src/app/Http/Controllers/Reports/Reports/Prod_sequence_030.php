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
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;
    protected $tableTrueWidth = true;
    protected $maxH = 30;
    protected $typeView = 'report-pivot';
    protected $type = 'prod_sequence';
    protected $pageLimit = 10;

    // DataSource
    public function getSqlStr($params)
    {
        // dd($params);
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
                        ,po.status AS prod_order_status
                        ,pose.status AS prod_sequence_status
                        #,pose.total_hours AS total_hours
                        

                        ,IF(prd.target_man_power,prd.target_man_power, NULL) AS target_man_power
                        ,FORMAT(ROUND((pru.worker_number), 2),2) AS actual_man_power
                        ,FORMAT(round((pru.worker_number - prd.target_man_power),2)*-1,2) AS vari_man_power
                        ,IF(100 - round((pru.worker_number / prd.target_man_power)*100,2),
                            100 - round((pru.worker_number / prd.target_man_power)*100,2),NULL
                            ) AS percent_vari_man_power


                        ,IF(prd.target_hours, prd.target_hours, NULL) AS target_hours
                        ,FORMAT(ROUND(SUM((TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60)),2),2) AS actual_total_hours
                        ,ROUND((ROUND(SUM((TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60)),2) - prd.target_hours),2)*-1 AS vari_hours
                        ,IF(100 - ROUND((ROUND(SUM((TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60)),2) / prd.target_hours)*100,2),
                            100 - ROUND((ROUND(SUM((TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60)),2) / prd.target_hours)*100,2),NULl
                            ) AS percent_vari_hours


                        ,IF(prd.target_man_hours, prd.target_man_hours, NULL) AS target_man_hours
                        ,FORMAT(ROUND(pru.worker_number * SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)),2),2) AS actual_man_hours
                        ,ROUND((ROUND(pru.worker_number * SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)),2) - prd.target_man_hours),2)*-1 AS vari_man_hours
                        ,IF(100 - ROUND((ROUND(pru.worker_number * SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)),2) / prd.target_man_hours)*100,2),
                            100 - ROUND((ROUND(pru.worker_number * SUM(ROUND(TIME_TO_SEC(TIMEDIFF(pru.end, pru.start)) / 60 / 60, 2)),2) / prd.target_man_hours)*100,2) , NULL
                            )AS percent_vari_man_hours

                        
                        ,FORMAT(pose.total_man_hours,2) AS total_man_hours
                        ,pd.id AS prod_discipline_id
                        ,pd.name AS prod_discipline_name


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
        if (isset($params['prod_order_id'])) $sql .= "\n AND po.id IN ({{prod_order_id}})";
        if (isset($params['prod_routing_id'])) $sql .= "\n AND po.prod_routing_id = {{prod_routing_id}}";
        if (isset($params['status'])) $sql .= "\n  AND pose.status IN ({{status}})";
        if (isset($params['prod_discipline_id'])) $sql .= "\n  AND prl.prod_discipline_id = ({{prod_discipline_id}})";
        elseif (!isset($params['status'])) $sql .= "\n AND pose.status IN ('in_progress', 'finished', 'on_hold')";

        $sql .= "\n     AND po.status IN ('in_progress', 'finished', 'on_hold')
                        AND SUBSTR(pru.date, 1, 10) <= '{{picker_date}}'
                        AND pose.deleted_by IS NULL";

        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND prl.id = {{prod_routing_link_id}}";
        $sql .= "\n GROUP BY project_id, sub_project_name, prod_order_id, prod_order_name, sub_project_id,prod_routing_link_id,pru.worker_number
                    ,target_hours
                    ,target_man_hours
                    ,target_man_power
                    ,total_man_hours
                    ORDER BY sub_project_name, prod_order_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] = date('d/m/Y');
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
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                'singleDatePicker' => true,
                'validation' => 'required|date_format:d/m/Y',
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
                // 'multiple' => true,
            ],
            [
                'title' => 'Production Routing Link',
                'dataIndex' => 'prod_routing_link_id',
                'allowClear' => true,
                'multiple' => true,
                'hasListenTo' => true,

            ],
            // [
            //     'title' => 'Status (for Production Order)',
            //     'dataIndex' => 'status',
            //     'allowClear' => true,
            //     'multiple' => true,
            // ],
            [
                'title' => 'Status (for Production Routing Link)',
                'dataIndex' => 'status',
                'allowClear' => true,
                'multiple' => true,
            ],
        ];
    }

    public function tableDataHeader($dataSource, $params)
    {
        return [
            'actual_man_power' => 'Actual',
            'target_man_power' => 'Target',
            'vari_man_power' => 'Variance',
            'percent_vari_man_power' => 'Var.%',

            'actual_total_hours' => 'Actual',
            'target_hours' => 'Target',
            'vari_hours' => 'Variance',
            'percent_vari_hours' => 'Var.%',

            'actual_man_hours' => 'Actual',
            'target_man_hours' => 'Target',
            'vari_man_hours' => 'Variance',
            'percent_vari_man_hours' => 'Var.%',
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
                    "width" => 120,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "left",
                    "width" => 150,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Status <br/> (Production Order)",
                    "dataIndex" => "prod_order_status",
                    "align" => "center",
                    "width" => 130,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Production Routing",
                    "dataIndex" => "prod_routing_name",
                    "align" => "left",
                    "width" => 250,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Discipline",
                    "dataIndex" => "prod_discipline_name",
                    "align" => "left",
                    "width" => 200,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Production Routing Link",
                    "dataIndex" => "prod_routing_link_name",
                    "align" => "left",
                    "width" => 300,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Status <br> (Production Routing Link)",
                    "dataIndex" => "prod_sequence_status",
                    "align" => "center",
                    "width" => 130,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Man-power  <br/>(AVG)",
                    "dataIndex" => "target_man_power",
                    "align" => "right",
                    "width" => 80,
                    "colspan" => 4,
                    'footer' => 'agg_avg',
                ],
                [
                    "dataIndex" => "actual_man_power",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_avg',

                ],
                [
                    "dataIndex" => "vari_man_power",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_avg',
                ],
                [
                    "dataIndex" => "percent_vari_man_power",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_avg',
                ],
                [
                    "title" => "Hours <br/>(SUM)",
                    "dataIndex" => "target_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                    "colspan" => 4,
                ],
                [
                    "dataIndex" => "actual_total_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "dataIndex" => "vari_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "dataIndex" => "percent_vari_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "title" => "Man-hours  <br/>(SUM)",
                    "dataIndex" => "target_man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                    "colspan" => 4,
                ],
                [
                    "dataIndex" => "actual_man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "dataIndex" => "vari_man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
                [
                    "dataIndex" => "percent_vari_man_hours",
                    "align" => "right",
                    "width" => 80,
                    'footer' => 'agg_sum',
                ],
            ];
    }
}
