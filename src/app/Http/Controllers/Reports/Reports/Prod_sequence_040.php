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
                        ,prl.id AS prod_routing_link_id
                        ,prl.name AS prod_routing_link_name
                        ,pr.id AS prod_routing_id    
                        ,pr.name AS prod_routing_name    
                        ,po.status AS prod_order_status
                        ,pose.status AS prod_sequence_status
                        #,pose.total_hours AS total_hours
                        ,pd.id AS prod_discipline_id
                        ,pd.name AS prod_discipline_name
                        ,po.total_calendar_days AS total_calendar_days
                        ,po.total_days_no_sun_no_ph AS independent_holiday_sunday_day
                        ,po.total_hours*60 AS net_working_day
                        ,po.total_days_have_ts AS total_days_have_ts
                        ,po.total_discrepancy_days AS total_discrepancy_days";
                    $sql .= "\n FROM sub_projects sp
                    JOIN prod_orders po ON po.sub_project_id = sp.id
                    LEFT JOIN prod_routings pr ON pr.id = po.prod_routing_id
                    LEFT JOIN projects pj ON sp.project_id = pj.id


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
        #if (isset($params['status'])) $sql .= "\n  AND pose.status IN ({{status}})";
        if (isset($params['prod_discipline_id'])) $sql .= "\n  AND prl.prod_discipline_id = ({{prod_discipline_id}})";
        elseif (!isset($params['status'])) $sql .= "\n AND po.status IN ('in_progress', 'finished', 'on_hold')";
        if(isset($params['status'])){
            foreach ($params['status'] as $status) {
                $sql .= "\n AND po.status = '$status'";
            }
        }
        $sql .= "\n
                        AND  SUBSTR(pru.date, 1, 10) >= '{$valOfParams["picker_date"]["start"]}'
                        AND  SUBSTR(pru.date, 1, 10) <= '{$valOfParams["picker_date"]["end"]}'
                        AND pose.deleted_by IS NULL";

        // if (isset($params['prod_routing_link_id'])) $sql .= "\n AND prl.id = {{prod_routing_link_id}}";
        $sql .= "\n GROUP BY project_id, sub_project_name, prod_order_id, prod_order_name, sub_project_id,prod_routing_link_id
                    ORDER BY sub_project_name, prod_order_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate();
        $params['project_id'] = $this->projectId;
        $params['sub_project_id'] = $this->subProjectId;
        // $params['prod_routing_id'] = $this->prodRoutingId;
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
                    "width" => 200,
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
                    'fixed' => 'left',

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
                    "title" => "Total Calendar Days",
                    "dataIndex" => "total_calendar_days",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Non-Sunday & Non-Holiday Workdays",
                    "dataIndex" => "independent_holiday_sunday_day",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Non-Stop Workdays",
                    "dataIndex" => "total_days_have_ts",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Net Working Days",
                    "dataIndex" => "net_working_day",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
                [
                    "title" => "Downtime Days",
                    "dataIndex" => "total_discrepancy_days",
                    "align" => "right",
                    "width" => 150,
                    "footer" => "agg_sum"
                ],
               
            ];
    }
}
