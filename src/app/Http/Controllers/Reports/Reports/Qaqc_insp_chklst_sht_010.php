<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Qaqc_insp_chklst_sht_010 extends Report_ParentReport2Controller
{
    protected $typeView = 'report-pivot';

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);

        $sql = "SELECT
                    pj.id AS project_id,
                    pj.name AS project_name,
                    sp.id AS sub_project_id,
                    sp.name AS sub_project_name,
                    pr.id AS prod_routing_id,
                    pr.name AS prod_routing_name,
                    qaqcitmp.id AS qaqc_insp_tmpl_id,
                    qaqcitmp.name AS qaqc_insp_tmpl_name,
                    po.id AS prod_order_id,
                    po.name AS prod_order_name,
                    qaqcicls.id AS sheet_id,
                    qaqcicls.name AS sheet_name,
                    qaqcicls.prod_discipline_id AS prod_discipline_id,
                    pdl.name AS prod_discipline_name,
                    qaqcicls.status AS status_chklst_sht,
                    qaqcicl.progress AS checklist_progress 

                    FROM qaqc_insp_chklsts qaqcicl

                    LEFT JOIN sub_projects sp ON sp.id = qaqcicl.sub_project_id
                    LEFT JOIN projects pj ON pj.id = sp.project_id
                    LEFT JOIN prod_routings pr ON pr.id = qaqcicl.prod_routing_id
                    LEFT JOIN prod_orders po ON po.id = qaqcicl.prod_order_id
                    LEFT JOIN qaqc_insp_chklst_shts qaqcicls ON qaqcicls.qaqc_insp_chklst_id = qaqcicl.id
                    LEFT JOIN qaqc_insp_tmpls qaqcitmp ON qaqcicl.qaqc_insp_tmpl_id = qaqcitmp.id
                    LEFT JOIN prod_disciplines pdl ON pdl.id = qaqcicls.prod_discipline_id
                    WHERE 1 = 1
        ";

        if ($pj = $valOfParams['project_id']) $sql .= "\n AND sp.project_id = $pj";
        if ($sub = $valOfParams['sub_project_id']) $sql .= "\n AND qaqcicl.sub_project_id = $sub";
        if ($pr = $valOfParams['prod_routing_id']) $sql .= "\n AND pr.id = $pr";
        if ($st = $valOfParams['qaqc_insp_tmpl_id']) $sql .= "\n AND qaqcicl.qaqc_insp_tmpl_id IN ($st)";

        // dd($sql);
        return $sql;
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
                'title' => 'Checklist Type ',
                'dataIndex' => 'qaqc_insp_tmpl_id',
                'hasListenTo' => true,
            ]
        ];
    }


    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "Project",
                "dataIndex" => "project_name",
                "align" => "left",
            ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "left",
                "width" => "150"
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_name",
                "align" => "left",
                "width" => "150"
            ],
            [
                "title" => "Checklist Type",
                "dataIndex" => "qaqc_insp_tmpl_name",
                "align" => "left",
                "width" => "150"
            ],
            [
                "title" => "Checklist Id",
                "dataIndex" => "qaqc_insp_tmpl_id",
                "align" => "center",
            ],
            [
                "title" => "Checklist Progress (%)",
                "dataIndex" => "checklist_progress",
                "align" => "right",
            ],
            [
                "title" => "Production Order",
                "dataIndex" => "prod_order_name",
                "align" => "left",
                "width" => "150"
            ],
            [
                "title" => "Sheet Id",
                "dataIndex" => "sheet_id",
                "align" => "center",
            ],
            [
                "title" => "Sheet Name",
                "dataIndex" => "sheet_name",
                "align" => "left",
                "width" => "150"
            ],
            [
                "title" => "Sheet Status",
                "dataIndex" => "status_chklst_sht",
                "align" => "center",

            ],
            [
                "title" => "Sheet Discipline",
                "dataIndex" => "prod_discipline_name",
                "align" => "left",
                "width" => "150"
            ],

        ];
    }

    public function getDisplayValueColumns()
    {
        return [
            [
                'sheet_id' => [
                    'route_name' => 'qaqc_insp_chklst_shts.edit',
                    'renderer' => 'id'
                ],
                'qaqc_insp_tmpl_id' => [
                    'route_name' => 'qaqc_insp_chklsts.edit',
                    'renderer' => 'id'
                ],
            ]
        ];
    }
}
