<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Http\Controllers\Reports\TraitConversionFieldNameGhgReport;
use App\Utils\Support\Report;

class Ghg_sheet_010 extends Report_ParentReport2Controller
{
	use TraitConversionFieldNameGhgReport;
    protected $mode='010';
    protected $maxH = 50;
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = false;

    public function getSqlStr($params)
    {
        $valOfParams = $this->generateValuesFromParamsReport($params);
        $sql = "SELECT
                    ghgtmpls.id  AS ghg_sheet_id,
                    ghgtmpls.name  AS ghg_sheet_name,
                    ghgmt.name AS ghg_metric_type_name,
                    ghgmt1.name AS ghg_metric_type_1_name,
                    ghgmt2.name AS ghg_metric_type_2_name,
                    SUBSTR(ghgs.ghg_month,1,7) AS ghg_month,
                    ghgs.name AS ghg_name,
                    ghgsl.ghg_sheet_id AS ghg_sheet_id_,
                    ghgmt.id AS ghg_metric_type_id,
                    ghgsl.ghg_metric_type_1_id AS ghg_metric_type_1_id,
                    ghgsl.ghg_metric_type_2_id AS ghg_metric_type_2_id,
                    terms.name AS unit,
                    ghgsl.factor AS factor,
                    ghgsl.value AS value,
                    ghgsl.total AS total,
                    ROUND(ghgsl.total/1000,3) AS tCo2e,
                    ghgsl.remark AS remark
                    FROM  ghg_sheets ghgs
                    JOIN ghg_tmpls ghgtmpls ON ghgs.ghg_tmpl_id = ghgtmpls.id
                    JOIN ghg_sheet_lines ghgsl ON ghgsl.ghg_sheet_id = ghgs.id

                    LEFT JOIN ghg_metric_type_1s ghgmt1 ON ghgmt1.id = ghgsl.ghg_metric_type_1_id
                    JOIN ghg_metric_types ghgmt ON ghgmt1.ghg_metric_type_id = ghgmt.id
                    LEFT JOIN ghg_metric_type_2s ghgmt2 ON ghgmt2.id = ghgsl.ghg_metric_type_2_id
                    LEFT JOIN terms terms ON terms.id = ghgsl.unit
                    WHERE 1 = 1";
    if(Report::checkParam($valOfParams, 'metric_type')) $sql .= "\n AND ghgmt.id = {{metric_type}}";
    if(Report::checkParam($valOfParams, 'metric_type1')) $sql .= "\n AND ghgmt1.id = {{metric_type1}}";
    if(Report::checkParam($valOfParams, 'metric_type2')) $sql .= "\n AND ghgmt2.id = {{metric_type2}}";

    if(Report::checkParam($valOfParams, 'ghg_tmpl')) $sql .= "\n AND ghgtmpls.id IN ({{ghg_tmpl}})";
    if(Report::checkParam($valOfParams, 'only_month')) $sql .= "\n AND SUBSTR(ghgs.ghg_month, 6 , 2) IN ({{only_month}})";
    if(Report::checkParam($valOfParams, 'year')) $sql .= "\n AND SUBSTR(ghgs.ghg_month, 1 , 4) IN ({{year}})";
        // dump($sql);
        $sql .= "\n ORDER BY ghg_month, ghg_sheet_name";
        return $sql;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
				'title' => 'Year',
				'dataIndex' => 'year',
                'multiple' => true,
			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'allowClear' => true,
				'multiple' => true,
			],
            [
                'title' => 'CO2 Emission Sheet',
                'dataIndex' => 'ghg_tmpl',
                // 'multiple' => true,
                'allowClear' => true,
            ],
            [
                'title' => 'Metric Type',
                'dataIndex' => 'metric_type',
                'allowClear' => true,
                'hasListenTo' => true,
            ],
            [
                'title' => 'Metric Type 1',
                'dataIndex' => 'metric_type1',
                'allowClear' => true,
                'hasListenTo' => true,
            ],
            [
                'title' => 'Metric Type 2',
                'dataIndex' => 'metric_type2',
                'allowClear' => true,
                'hasListenTo' => true,
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
                "title" => "CO2 Emission Sheet",
                "dataIndex" => "ghg_sheet_name",
                "align" => "left",
                "width" =>300,
            ],
               [
                'title' => 'Metric Type',
                'dataIndex' => 'ghg_metric_type_name',
                "align" => "left",
                "width" =>200,
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
                "title" => "Total <br/>(Kg/CO2)",
                "dataIndex" => "total",
                "align" => "right",
                "width" =>100,
                "footer" => "agg_sum",
            ],
            [
                "title" => "Total <br/>(tCO2e)",
                "dataIndex" => "tCo2e",
                "align" => "right",
                "width" =>100,
                "footer" => "agg_sum",
                'decimal' => "3"
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
        $params['year'] = date("Y");
        return $params;
    }

    public function getDisplayValueColumns()
    {
        return [
            [
                'ghg_sheet_name' => [
                    'route_name' => 'ghg_tmpls.edit'
                ],
                'ghg_metric_type_name' => [
                    'route_name' => 'ghg_metric_types.edit'
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
    public  function changeDataSource($dataSource, $params)
    {
        $dataSource = $this->convertNames($dataSource);
        return $dataSource;
    }
}
