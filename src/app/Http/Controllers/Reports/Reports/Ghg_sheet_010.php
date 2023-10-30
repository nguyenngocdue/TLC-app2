<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;

class Ghg_sheet_010 extends Report_ParentReport2Controller
{
    protected $mode='010';
    protected $maxH = 50;
    protected $tableTrueWidth = false;

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        $sql = "SELECT
                    ghgtmpls.id  AS ghg_sheet_id,
                    ghgtmpls.name  AS ghg_sheet_name,
                    ghgmt1.name AS ghg_metric_type_1_name,
                    ghgmt2.name AS ghg_metric_type_2_name,
                    SUBSTR(ghgs.ghg_month,1,7) AS ghg_month,
                    ghgs.name AS ghg_name,
                    ghgsl.ghg_sheet_id AS ghg_sheet_id_,
                    ghgsl.ghg_metric_type_1_id AS ghg_metric_type_1_id,
                    ghgsl.ghg_metric_type_2_id AS ghg_metric_type_2_id,
                    terms.name AS unit,
                    ghgsl.factor AS factor,
                    ghgsl.value AS value,
                    ghgsl.total AS total,
                    ROUND(ghgsl.total/1000,2) AS tCo2e,
                    ghgsl.remark AS remark
                    FROM  ghg_sheets ghgs
                    JOIN ghg_tmpls ghgtmpls ON ghgs.ghg_tmpl_id = ghgtmpls.id
                    JOIN ghg_sheet_lines ghgsl ON ghgsl.ghg_sheet_id = ghgs.id
                    LEFT JOIN ghg_metric_type_2s ghgmt2 ON ghgmt2.id = ghgsl.ghg_metric_type_2_id
                    LEFT JOIN ghg_metric_type_1s ghgmt1 ON ghgmt1.id = ghgsl.ghg_metric_type_1_id
                    LEFT JOIN terms terms ON terms.id = ghgsl.unit
                    WHERE 1 = 1";
    if(isset($valOfParams['ghg_tmpl']) && $valOfParams['ghg_tmpl']) $sql .= "\n AND ghgtmpls.id IN ({{ghg_tmpl}})";
    if(isset($valOfParams['month']) && $valOfParams['month']) $sql .= "\n AND SUBSTR(ghgs.ghg_month, 1 , 7) =  '{{month}}'";
        // dump($sql);
        $sql .= "\n ORDER BY ghg_month DESC, ghg_sheet_name";
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
                'title' => 'CO2 Emission Sheet',
                'dataIndex' => 'ghg_tmpl',
                'multiple' => true,
                'allowClear' => true,
            ]
        ];
    }


    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                "title" => "Month",
                "dataIndex" => "ghg_month",
                "align" => "right",
                "width" =>150,
            ],
            [
                "title" => "Name",
                "dataIndex" => "ghg_sheet_name",
                "align" => "left",
                "width" =>300,
            ],
            [
                "title" => "Metric Type 1",
                "dataIndex" => "ghg_metric_type_1_name",
                "align" => "left",
                "width" =>200,
            ],
            [
                "title" => "Metric Type 2",
                "dataIndex" => "ghg_metric_type_2_name",
                "align" => "left",
                "width" =>200,
            ],
            [
                "title" => "Unit",
                "dataIndex" => "unit",
                "align" => "left",
                "width" =>120,
            ],
            [
                "title" => "Factor",
                "dataIndex" => "factor",
                "align" => "right",
                "width" =>100,
            ],
            [
                "title" => "Value",
                "dataIndex" => "value",
                "align" => "right",
                "width" =>100,
                "footer" => "agg_sum"
            ],
            [
                "title" => "Total",
                "dataIndex" => "total",
                "align" => "right",
                "width" =>100,
                "footer" => "agg_sum"
            ],
            [
                "title" => "tCO2e",
                "dataIndex" => "tCo2e",
                "align" => "right",
                "width" =>100,
                "footer" => "agg_sum"
            ],
            [
                "title" => "Remark",
                "dataIndex" => "remark",
                "align" => "left",
                "width" =>300,
            ],
        ];
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['month'] = date("Y-m");
        return $params;
    }

    public function getDisplayValueColumns()
    {
        return [
            [
                'ghg_sheet_name' => [
                    'route_name' => 'ghg_tmpls.edit'
                ],
                'ghg_metric_type_1_name' => [
                    'route_name' => 'ghg_metric_type_1s.edit'
                ],
                'ghg_metric_type_2_name' => [
                    'route_name' => 'ghg_metric_type_2s.edit'
                ],
            ]
        ];
    }
}
