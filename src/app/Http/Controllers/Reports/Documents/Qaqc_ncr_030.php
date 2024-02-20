<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource as DocumentsQaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\TraitCreateChartFromGrafana;
use App\Utils\Support\Report;

class Qaqc_ncr_030 extends Report_ParentDocument2Controller
{

    use TraitCreateChartFromGrafana;
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

    public function getTableColumns($params, $dataSource)
    {
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
            "RESPONSIBLE_TEAM" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Fit Out',
                    'dataIndex' => 'count_fit_out',
                    'align' => 'right'
                ],
                [
                    'title' => 'PPR',
                    'dataIndex' => 'count_ppr',
                    'align' => 'right'
                ],
                [
                    'title' => 'Structure',
                    'dataIndex' => 'count_structure',
                    'align' => 'right'
                ]

            ],
            "AVERAGE_CLOSED_ISSUES" => [
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Closed Days (AVG)',
                    'dataIndex' => 'avg_closed',
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
                    'title' => 'ISP',
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


    public function getDataSource($params)
    {
        $_primaryData = new Qaqc_ncr_030_dataSource();
        $primaryData = $_primaryData->getDataSource($params);
        // dd($primaryData);
        return $primaryData;
    }

    public function changeParams($params)
    {
        $params['condition_months'] = 'var-month=All';
        if (Report::checkValueOfField($params, 'only_month')) {
            $onlyMonthStr = implode(',', $params['only_month']);
            $onlyMonthStr = 'var-month=' . str_replace(',', '&var-month=', $onlyMonthStr);
            $params['condition_months'] = $onlyMonthStr;
        }
        return $params;
    }
}
