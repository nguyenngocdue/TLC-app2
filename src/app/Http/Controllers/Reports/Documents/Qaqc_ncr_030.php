<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource as DocumentsQaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\TraitCreateChartFromGrafana;
use App\Http\Controllers\Reports\TraitParamURLGrafana;
use App\Utils\Support\Report;

class Qaqc_ncr_030 extends Report_ParentDocument2Controller
{

    use TraitCreateChartFromGrafana;
    use TraitParamURLGrafana;
    protected $mode = '030';
    protected $projectId = 5;
    protected $viewName = 'document-ncr-030';

    protected function getParamColumns($dataSource, $params)
    {
        return [
            [
                'title' => 'Year',
                'dataIndex' => 'year',
            ],
            [
                'title' => 'Month',
                'dataIndex' => 'only_month',
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Project",
                "dataIndex" => "project_id",
                "allowClear" => true,
                "multiple" => true,
            ],
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_id",
                "hasListenTo" => true,
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Routing",
                "dataIndex" => "prod_routing_id",
                "hasListenTo" => true,
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Order",
                "dataIndex" => "prod_order_id",
                "multiple" => true,
                "hasListenTo" => true,
                "multiple" => true,
                "allowClear" => true,
            ],
            [
                "title" => "Production Discipline",
                "dataIndex" => "prod_discipline_id",
                "multiple" => true,
                "allowClear" => true,
            ],
            // [
            //     "title" => "Responsible Team",
            //     "dataIndex" => "user_team_ncr",
            //     "multiple" => true,
            //     "allowClear" => true,
            // ],
            // [
            //     "title" => "Root Cause",
            //     "dataIndex" => "root_cause",
            //     "multiple" => true,
            //     "allowClear" => true,
            // ],
            // [
            //     "title" => "Report Type",
            //     "dataIndex" => "report_type",
            //     "multiple" => true,
            //     "allowClear" => true,
            // ],
            // [
            //     "title" => "Status",
            //     "dataIndex" => "status",
            //     "multiple" => true,
            //     "allowClear" => true,
            // ],
        ];
    }

    public function getTableColumns($params, $dataSource)
    {
        if (isset($dataSource['RESPONSIBLE_TEAM'])) {
            $colResponsible = $this->getColumnFieldsForResponsibleTeam($dataSource['RESPONSIBLE_TEAM']);
        }

        return [
            "OPEN_CLOSED_ISSUES" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Open Issues',
                    'dataIndex' => 'count_closed',
                    'align' => 'right'
                ],
                [
                    'title' => 'Closed Issues',
                    'dataIndex' => 'count_new',
                    'align' => 'right'
                ]

            ],
            "NCR_DR" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'NCR',
                    'dataIndex' => 'count_defect',
                    'align' => 'right'
                ],
                [
                    'title' => 'DR',
                    'dataIndex' => 'count_ncr',
                    'align' => 'right'
                ]

            ],
            "RESPONSIBLE_TEAM" => array_merge([[
                'title' => 'Month',
                'dataIndex' => 'str_month',
                'align' => 'center'
            ]], $colResponsible),
            "AVERAGE_CLOSED_ISSUES" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Closed Days (AVG)',
                    'dataIndex' => 'avg_day_closed',
                    'align' => 'right'
                ],
                [
                    'title' => 'New',
                    'dataIndex' => 'count_ncr_open',
                    'align' => 'right'
                ]
            ],
            "ISSUES_STATUS" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Closed',
                    'dataIndex' => 'count_closed',
                    'align' => 'right'
                ],
                [
                    'title' => 'New',
                    'dataIndex' => 'count_new',
                    'align' => 'right'
                ],
                [
                    'title' => 'Rejected',
                    'dataIndex' => 'count_rejected',
                    'align' => 'right'
                ],
                [
                    'title' => 'Resolved',
                    'dataIndex' => 'count_resolved',
                    'align' => 'right'
                ]
            ],
            "ISSUES_SOURCE" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'ICS',
                    'dataIndex' => 'sum_count_isp',
                    'align' => 'right'
                ],
                [
                    'title' => 'MIR',
                    'dataIndex' => 'sum_count_mir',
                    'align' => 'right'
                ],
                [
                    'title' => 'WIR',
                    'dataIndex' => 'sum_count_wir',
                    'align' => 'right'
                ],
                [
                    'title' => 'Grand Total',
                    'dataIndex' => 'grand_total',
                    'align' => 'right'
                ]
            ]

        ];
    }

    private function getColumnFieldsForResponsibleTeam($data)
    {
        $fieldColumns = [];
        $cols = [];
        foreach ($data as &$values) {
            $_values = (array)$values;
            if (Report::checkValueOfField($_values, 'defect_report_type')) {
                if (!in_array($_values['defect_report_type'], $fieldColumns)) {
                    $fieldColumns[] = $_values['defect_report_type'];
                    $cols[] = [
                        'title' => $_values['defect_report_type'],
                        'dataIndex' => $_values['defect_report_type'],
                        'align' => 'right',
                    ];
                }
            }
        }
        return $cols;
    }

    private function getDataResponsibleTeam($data)
    {
        foreach ($data as &$values) {
            $_values = (array)$values;
            if (Report::checkValueOfField($_values, 'defect_report_type')) {
                $values->{$_values['defect_report_type']} = $_values['count_ncr_dr'];
            }
        }
        $groupByField = Report::groupArrayByKey($data, "date_time");
        $data = array_map(fn ($item) => array_merge(...$item), $groupByField);
        return $data;
    }

    public function getDataSource($params)
    {
        $_primaryData = new Qaqc_ncr_030_dataSource();
        $primaryData = $_primaryData->getDataSource($params);
        foreach ($primaryData as $key => $values) {
            if ($key === 'RESPONSIBLE_TEAM') {
                $dataResponsibleTeam = $this->getDataResponsibleTeam($values);
                $primaryData[$key] = $dataResponsibleTeam;
            }
        }
        return $primaryData;
    }

    public function getParamsGrafana($params)
    {
        return $this->createParamsGrafana($params);
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['year'] = 2023;
        return $params;
    }
}
