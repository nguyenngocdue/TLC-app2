<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;

class Qaqc_ncr_010 extends Report_ParentReport2Controller
{
    protected $tableTrueWidth = true;
    protected $maxH = 50;
    protected $projectId = 8;
    protected $subProjectId = 107;
    protected $prodRoutingId = 62;

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        // dd($valOfParams, $params);
            $sql = "SELECT tb1.*
                    ,pj.name AS project_name
                    ,sp.name AS sub_project_name
                    ,pr.name AS prod_routing_name
                    ,po.name AS prod_order_name
                    ,pdisc.name AS prod_discipline_name
                    ,ustncr.name AS user_team_name
                    ,prio.name AS priority_name
                    ,term_root_cause.name AS root_cause_name,
                    term_disposition.name AS disposition_name,
                    term_severity.name AS severity_name,
                    term_report_type.name AS report_type_name,
                    UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '_', -1), '\\\', 1)) AS parent_type,
                    CONCAT('TLC-',sp.name,'-',UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(tb1.parent_type, '_', -1), '\\\', 1)),'-',
                        LPAD(tb1.doc_id, 4, '0')) AS doc_type 
                    FROM (SELECT
                    DATE_FORMAT(SUBSTR(ncr.created_at, 1, 10), '%d/%m/%Y') AS create_date,
                        ncr.parent_type AS parent_type,
                        ncr.id AS ncr_id,
                        ncr.doc_id AS doc_id,
                        ncr.name AS ncr_name,
                        ncr.parent_id AS parent_id,
                        ncr.project_id AS project_id,
                        ncr.sub_project_id AS sub_project_id,
                        ncr.prod_routing_id AS prod_routing_id,
                        ncr.prod_order_id AS prod_order_id,
                        ncr.prod_discipline_id AS prod_discipline_id,
                        ncr.user_team_id AS user_team_id,
                        ncr.priority_id AS priority_id,
                        DATE_FORMAT(SUBSTR(ncr.due_date, 1, 10), '%d/%m/%Y') AS due_date,
                        ncr.defect_root_cause_id AS root_cause,
                        ncr.defect_disposition_id AS disposition_id,
                        ncr.defect_severity AS severity,
                        ncr.defect_report_type AS report_type,
                        ncr.qty_man_power AS qty_man_power,
                        ncr.hour_per_man AS hour_per_man,
                        ncr.total_hour AS total_hour,
                        ncr.status AS ncr_status,
                        DATE_FORMAT(SUBSTR(ncr.closed_at, 1, 10), '%d/%m/%Y') AS closed_at
                        FROM qaqc_ncrs ncr
                    WHERE 1 = 1
                        AND SUBSTR(ncr.created_at, 1, 10) >= '{$valOfParams["picker_date"]["start"]}'
                        AND SUBSTR(ncr.created_at, 1, 10) <= '{$valOfParams["picker_date"]["end"]}'";
        if (Report::checkValueOfField($valOfParams, 'project_id')) $sql .= "\n AND ncr.project_id = {$valOfParams['project_id']}";
        if (Report::checkValueOfField($valOfParams, 'sub_project_id')) $sql .= "\n AND ncr.sub_project_id = {$valOfParams['sub_project_id']}";
        if (Report::checkValueOfField($valOfParams, 'prod_routing_id')) $sql .= "\n AND ncr.prod_routing_id = {$valOfParams['prod_routing_id']}";
        if (Report::checkValueOfField($valOfParams, 'prod_order_id')) $sql .= "\n AND ncr.prod_order_id IN ({$valOfParams['prod_order_id']})";
        if (Report::checkValueOfField($valOfParams, 'prod_discipline_id'))  $sql .= "\n AND ncr.prod_discipline_id IN ({$valOfParams['prod_discipline_id']})";
        if (Report::checkValueOfField($valOfParams, 'user_team_ncr'))  $sql .= "\n AND ncr.user_team_id IN ({$valOfParams['user_team_ncr']})";
        if (Report::checkValueOfField($valOfParams, 'report_type'))  $sql .= "\n AND ncr.defect_report_type IN ({$valOfParams['report_type']})";
        if (Report::checkValueOfField($valOfParams, 'status'))  $sql .= "\n AND ncr.status IN ({$valOfParams['status']})";

        $sql .= "\n ) AS tb1
                    LEFT JOIN projects pj ON pj.id = tb1.project_id
                    LEFT JOIN sub_projects sp ON sp.id = tb1.sub_project_id
                    LEFT JOIN prod_orders po ON po.id = tb1.prod_order_id 
                    LEFT JOIN prod_routings pr ON pr.id = tb1.prod_routing_id
                    LEFT JOIN prod_disciplines pdisc ON pdisc.id = tb1.prod_discipline_id
                    LEFT JOIN user_team_ncrs ustncr ON ustncr.id = tb1.user_team_id
                    LEFT JOIN priorities prio ON prio.id = tb1.priority_id
                    LEFT JOIN terms term_root_cause ON term_root_cause.id = tb1.root_cause
                    LEFT JOIN terms term_disposition ON term_disposition.id = tb1.disposition_id
                    LEFT JOIN terms term_severity ON term_severity.id = tb1.severity
                    LEFT JOIN terms term_report_type ON term_report_type.id = tb1.report_type
                ";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                "allowClear" => false,
                'validation' => 'date_format:d/m/Y',
            ],
            [
                "title" => "Project",
                "dataIndex" => "project_id",
                "allowClear" => true,
            ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_id",
                "hasListenTo" => true,
                // "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_id",
                "hasListenTo" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Order",
                "dataIndex" => "prod_order_id",
                "multiple" => true,
                "hasListenTo" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Discipline",
                "dataIndex" => "prod_discipline_id",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Responsible Team",
                "dataIndex" => "user_team_ncr",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Root Cause",
                "dataIndex" => "root_cause",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Report Type",
                "dataIndex" => "report_type",
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Status",
                "dataIndex" => "status",
                "multiple" => true,
                "allowClear" => true,
            ],
        ];
    }


    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "Create Date",
                "dataIndex" => "create_date",
                "align" => "right",
                "width" => 120,
            ],
            [
                "title" => "Source Form",
                "dataIndex" => "parent_type",
                "align" => "left",
                "width" => 120,
            ],
            [
                "title" => "Doc ID",
                "dataIndex" => "doc_type",
                "align" => "left",
                "width" => 180,
            ],
            [
                "title" => "Title",
                "dataIndex" => "ncr_name",
                "align" => "left",
                "width" => 200,
            ],
            [
                "title" => "Project",
                "dataIndex" => "project_name",
                "align" => "left",
                "width" => 140,
            ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "left",
                "width" => 140,
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_name",
                "align" => "left",
                "width" => 180,
            ],
            [
                "title" => "Production Order",
                "dataIndex" => "prod_order_name",
                "align" => "left",
                "width" => 180,
            ],
            [
                "title" => "Production Discipline",
                "dataIndex" => "prod_discipline_name",
                "align" => "left",
                "width" => 250,
            ],
            [
                "title" => "Responsible Team",
                "dataIndex" => "user_team_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Priority",
                "dataIndex" => "priority_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Due Date",
                "dataIndex" => "due_date",
                "align" => "right",
                "width" => 140,
            ],
            [
                "title" => "Root Cause",
                "dataIndex" => "root_cause_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Disposition",
                "dataIndex" => "disposition_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Severity",
                "dataIndex" => "severity_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Report Type",
                "dataIndex" => "report_type_name",
                "align" => "left",
                "width" => 150,
            ],
            [
                "title" => "Qty Manpower",
                "dataIndex" => "qty_man_power",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Hour/Man",
                "dataIndex" => "hour_per_man",
                "align" => "right",
                "width" => 120,
            ],
            [
                "title" => "Total Hours",
                "dataIndex" => "total_hour",
                "align" => "right",
                "width" => 120,
            ],
            [
                "title" => "Status",
                "dataIndex" => "ncr_status",
                "align" => "center",
                "width" => 120,
            ],
            [
                "title" => "Closed Date",
                "dataIndex" => "closed_at",
                "align" => "right",
                "width" => 150,
            ],
        ];
    }
   
    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] =DateReport::defaultPickerDate();
        #$params['project_id'] = $this->projectId;
        #$params['sub_project_id'] = $this->subProjectId;
        #$params['prod_routing_id'] = $this->prodRoutingId;
        // dd($params);
        return $params;
    }

}
