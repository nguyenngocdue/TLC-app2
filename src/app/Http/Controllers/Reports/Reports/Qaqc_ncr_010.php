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

    public function getDataSource($params)
    {
        $primaryData = new Qaqc_ncr_dataSource();
        $_primaryData = $primaryData->getDataSource($params);
        $primaryData = $primaryData->changeDataSource($_primaryData, $params);
        // dump($primaryData);
        return $primaryData;
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
                "width" => 100,
            ],
            [
                "title" => "Source Form",
                "dataIndex" => "parent_type",
                "align" => "left",
                "width" => 80,
            ],
            [
                "title" => "Doc ID",
                "dataIndex" => "doc_type",
                "align" => "left",
                "width" => 180,
            ],
            [
                "title" => "NCR ID",
                "dataIndex" => "ncr_id",
                "align" => "center",
                "width" => 100,
            ],
            [
                "title" => "Title",
                "dataIndex" => "ncr_name",
                "align" => "left",
                "width" => 400,
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
                "width" => 180,
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
                "width" => 100,
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
                "width" => 100,
            ],
            [
                "title" => "Severity",
                "dataIndex" => "severity_name",
                "align" => "left",
                "width" => 100,
            ],
            [
                "title" => "Report Type",
                "dataIndex" => "report_type_name",
                "align" => "left",
                "width" => 100,
            ],
            [
                "title" => "Qty Manpower",
                "dataIndex" => "qty_man_power",
                "align" => "right",
                "width" => 100,
                "footer" => "agg_sum",
            ],
            [
                "title" => "Hour/Man",
                "dataIndex" => "hour_per_man",
                "align" => "right",
                "width" => 100,
                "footer" => "agg_sum",
            ],
            [
                "title" => "Total Hours",
                "dataIndex" => "total_hour",
                "align" => "right",
                "width" => 100,
                "footer" => "agg_sum",
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
                "width" => 100,
            ],
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['picker_date'] = DateReport::defaultPickerDate();
        #$params['project_id'] = $this->projectId;
        #$params['sub_project_id'] = $this->subProjectId;
        #$params['prod_routing_id'] = $this->prodRoutingId;
        // dd($params);
        return $params;
    }

    public function getDisplayValueColumns()
    {
        return [
            [
                'ncr_id' => [
                    'route_name' => 'qaqc_ncrs.edit',
                    'renderer' => 'id',
                ]
            ]
        ];
    }
}
