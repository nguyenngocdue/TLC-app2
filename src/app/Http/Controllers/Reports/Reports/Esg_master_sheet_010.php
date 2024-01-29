<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Utils\Support\Report;

class Esg_master_sheet_010 extends Report_ParentReport2Controller
{
    protected $mode = '010';
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = false;

    public function getSqlStr($params)
    {
        [$month, $projectId] = $this->selectMonth($params);
        $sql = "SELECT
        SUBSTR(esgms.esg_month,1,7) AS esg_month,
        esgs.id AS esg_sheet_id,
        esgs.name AS esg_sheet_name,
        
        esgt.id AS esg_tmpl_id, 
        esgt.name AS esg_tmpl_name,
        
        esgm.id AS esg_metric_type_id,
        esgm.name AS esg_metric_type_name,
        
        esgl.id AS esg_sheet_line_id,
        esgl.value AS esg_sheet_line_value,
        
        esgm.unit AS unit,
        te.name AS unit_name,
        te1.name AS esg_state_name,

        wp.id AS workplace_id,
        wp.name AS workplace_name
        
        FROM esg_sheet_lines esgl
        LEFT JOIN esg_tmpls esgt ON esgt.id = esgl.esg_tmpl_id
        LEFT JOIN esg_sheets esgs ON esgs.id = esgl.esg_sheet_id AND esgt.id = esgs.esg_tmpl_id
        LEFT JOIN esg_metric_types esgm ON esgm.id = esgl.esg_metric_type_id AND esgm.esg_tmpl_id = esgt.id
        LEFT JOIN esg_tmpl_lines esgtl ON esgtl.esg_metric_type_id = esgm.id AND esgtl.esg_tmpl_id = esgt.id
        LEFT JOIN esg_master_sheets esgms ON esgms.id = esgs.esg_master_sheet_id AND esgms.esg_tmpl_id = esgt.id
        LEFT JOIn terms te ON te.id = esgm.unit
        LEFT JOIN terms te1 ON te1.id = esgm.esg_state
        LEFT JOIN workplaces wp ON wp.id = esgms.workplace_id
        WHERE 1 = 1
        AND esgl.deleted_by IS NULL
        AND esgs.deleted_by IS NULL
        AND esgm.deleted_by IS NULL";

        if (Report::checkParam($params, 'month')) $sql .= "\n AND SUBSTR(esgms.esg_month,1,7) = {{month}}";

        if (Report::checkParam($params, 'ESG_sheet_id')) $sql .= "\n AND esgs.id IN ({{ESG_sheet_id}})";
        if (Report::checkParam($params, 'ESG_tmpl_id')) $sql .= "\n AND esgt.id IN ({{ESG_tmpl_id}})";
        if (Report::checkParam($params, 'ESG_metric_type_id')) $sql .= "\n AND esgm.id IN ({{ESG_metric_type_id}})";
        if (Report::checkParam($params, 'workplace_id')) $sql .= "\n AND wp.id IN ({{workplace_id}})";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Month',
                'dataIndex' => 'month',
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'workplace_id',
                'multiple' => true,
            ],
            [
                'title' => 'Template',
                'dataIndex' => 'ESG_tmpl_id',
                // 'multiple' => true,
            ],
            [
                'title' => 'Sheet',
                'dataIndex' => 'ESG_sheet_id',
                // 'multiple' => true,
                'hasListenTo' => true,

            ],
            [
                'title' => 'Metric Type',
                'dataIndex' => 'ESG_metric_type_id',
                'multiple' => true,
            ],
        ];
    }


    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "Month",
                "dataIndex" => "esg_month",
                "align" => "center",
                "width" => 200,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_name",
                "align" => "left",
                "width" => 200,
            ],
            [
                "title" => "Sheet",
                "dataIndex" => "esg_sheet_name",
                "align" => "left",
                "width" => 250,
            ],
            [
                "title" => "Template",
                "dataIndex" => "esg_tmpl_name",
                "align" => "left",
                "width" => 250,
            ],
            [
                "title" => "Metric Type Name",
                "dataIndex" => "esg_metric_type_name",
                "align" => "left",
                "width" => 200,
            ],
            [
                "title" => "Unit",
                "dataIndex" => "unit_name",
                "align" => "left",
                "width" => 10,
            ],
            [
                "title" => "State",
                "dataIndex" => "esg_state_name",
                "align" => "left",
                "width" => 10,
            ],
            [
                "title" => "Value",
                "dataIndex" => "esg_sheet_line_value",
                "align" => "right",
                "width" => 10,
                "footer" => "agg_sum"
            ],
        ];
    }
    public function getDisplayValueColumns()
    {
        return [
            [
                'esg_sheet_name' => [
                    'route_name' => 'esg_sheets.edit'
                ],
                'esg_tmpl_name' => [
                    'route_name' => 'esg_tmpls.edit'
                ],
                'esg_metric_type_name' => [
                    'route_name' => 'esg_metric_types.edit'
                ],
            ]
        ];
    }
}
