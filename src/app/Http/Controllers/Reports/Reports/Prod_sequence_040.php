<?php

namespace App\Http\Controllers\Reports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\DateReport;

class Prod_sequence_040 extends Report_ParentReport2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitForwardModeReport;

    protected $mode = '040';
    protected $projectId = 5;
    protected $subProjectId = 21;
    protected $prodRoutingId = 2;
    protected $tableTrueWidth = true;
    protected $maxH = 30;
    protected $typeView = 'report-pivot';
    protected $type = 'prod_sequence';
    protected $pageLimit = 10;

    // DataSource
    public function getSqlStr($params)
    {
        $valOfParams = DateReport::createValueForParams([
            'sub_project_id',
            'project_id',
            'prod_routing_id',
            'prod_order_id',
            'prod_routing_link_id',
            'erp_routing_link_id',
            'prod_discipline_id',
            'picker_date',
            'status',
        ], $params);
        
        // dd($valOfParams, $params);
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
                        ,pd.id AS prod_discipline_id
                        ,pd.name AS prod_discipline_name
                        ,null AS total_calendar_days
                        ,null AS independent_holiday_sunday_day
                        ,null AS net_working_day
                        ,null AS downtime_day

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
                        AND  SUBSTR(pru.date, 1, 10) >= '{$valOfParams["picker_date"]["start"]}'
                        AND  SUBSTR(pru.date, 1, 10) <= '{$valOfParams["picker_date"]["end"]}'
                        AND pose.deleted_by IS NULL";

        if (isset($params['prod_routing_link_id'])) $sql .= "\n AND prl.id = {{prod_routing_link_id}}";
        $sql .= "\n GROUP BY project_id, sub_project_name, prod_order_id, prod_order_name, sub_project_id,prod_routing_link_id,pru.worker_number
                    ORDER BY sub_project_name, prod_order_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate();
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
                'singleDatePicker' => false,
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

    public function getTableColumns($params, $dataSource)
    {
        return
            [
                [
                    "title" => "Sub Project",
                    "dataIndex" => "sub_project_name",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left'
                ],
                [
                    "title" => "Production Order",
                    "dataIndex" => "prod_order_name",
                    "align" => "left",
                    "width" => 100,
                    'fixed' => 'left',
                ],
                [
                    "title" => "Status <br/> (Production Order)",
                    "dataIndex" => "prod_order_status",
                    "align" => "left",
                    "width" => 100,
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
                ],
                [
                    "title" => "Status <br> (Production Routing Link)",
                    "dataIndex" => "prod_sequence_status",
                    "align" => "left",
                    "width" => 100,
                ],
                [
                    "title" => "Total Calendar Days",
                    "dataIndex" => "total_calendar_days",
                    "align" => "left",
                    "width" => 300,
                ],
                [
                    "title" => "Days <br/> (independent holiday and sunday)",
                    "dataIndex" => "independent_holiday_sunday_day",
                    "align" => "left",
                    "width" => 300,
                ],
                [
                    "title" => "Net Working Days",
                    "dataIndex" => "net_working_day",
                    "align" => "left",
                    "width" => 300,
                ],
                [
                    "title" => "Downtime Days",
                    "dataIndex" => "downtime_day",
                    "align" => "left",
                    "width" => 300,
                ],
               
            ];
    }
}
