<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource as DocumentsQaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Documents\Qaqc_ncr_030_dataSource;
use App\Http\Controllers\Reports\TraitCreateChartFromGrafana;
use App\Http\Controllers\Reports\TraitParamURLGrafana;
use App\Models\Prod_discipline;
use App\Models\Prod_routing;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Qaqc_ncr_030 extends Report_ParentDocument2Controller
{

    use TraitCreateChartFromGrafana;
    use TraitParamURLGrafana;
    protected $mode = '030';
    protected $projectId = 5;
    protected $viewName = 'document-ncr-030';
    protected $optionPrint = "landscape";

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
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Total',
                    'dataIndex' => 'total',
                    'align' => 'center',
                    'width' => '50',
                ],
                [
                    'title' => 'Closed Issues',
                    'dataIndex' => 'count_closed',
                    'align' => 'right'
                ],
                [
                    'title' => 'Open Issues',
                    'dataIndex' => 'count_new',
                    'align' => 'right'
                ]

            ],
            "NCR_DR" => [
                [
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Total',
                    'dataIndex' => 'total',
                    'align' => 'center'
                ],
                [
                    'title' => 'NCR',
                    'dataIndex' => 'count_ncr',
                    'align' => 'right'
                ],
                [
                    'title' => 'DR',
                    'dataIndex' => 'count_defect',
                    'align' => 'right'
                ],

            ],
            "RESPONSIBLE_TEAM" => array_merge([
                [
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Total',
                    'dataIndex' => 'total',
                    'align' => 'center'
                ],
            ], $colResponsible),
            "AVERAGE_CLOSED_ISSUES" => [
                [
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
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
                    'title' => 'Open Issues',
                    'dataIndex' => 'count_ncr_open',
                    'align' => 'right'
                ]
            ],
            "ISSUES_STATUS" => [
                [
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Total',
                    'dataIndex' => 'total',
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
                    'title' => 'Year',
                    'dataIndex' => 'year',
                    'align' => 'center'
                ],
                [
                    'title' => 'Month',
                    'dataIndex' => 'str_month',
                    'align' => 'center'
                ],
                [
                    'title' => 'Total',
                    'dataIndex' => 'grand_total',
                    'align' => 'right'
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

            ]

        ];
    }


    private function getNamesUserTeamsNCR()
    {
        $sqlStr = "SELECT
                    DISTINCT ncrt.name AS name
                    FROM qaqc_ncrs ncr
                    LEFT JOIN user_team_ncrs ncrt ON ncrt.id = ncr.user_team_id
                    WHERE 1 = 1
                    AND  ncr.deleted_by IS NULL";
        $sqlData = DB::select(DB::raw($sqlStr));
        return collect($sqlData);
    }

    private function getColumnFieldsForResponsibleTeam($data)
    {
        $firstData = reset($data);
        if (empty($firstData)) return [[]];
        $allNamesUserTeams = $this->getNamesUserTeamsNCR()->pluck('name')->toArray();
        $cols = array_filter($allNamesUserTeams, function ($value) use ($firstData) {
            return array_key_exists($value, $firstData);
        });
        sort($cols);
        $cols = array_map(function ($item) {
            return  [
                'title' => $item,
                'dataIndex' => $item,
                'align' => 'right'
            ];
        }, $cols);
        return $cols;
    }

    private function getDataResponsibleTeam($data)
    {
        $fields = [];
        foreach ($data as &$record) {
            if (Report::checkValueOfField((array)$record, 'user_team_name')) {
                $field = $record->user_team_name;
                if (!in_array($field, $fields)) $fields[] = $field;
                $record->{$field} = $record->count_ncr_dr;
            }
        }

        $groupedByDateTime = Report::groupArrayByKey($data, "date_time");
        $mergedData = array_map(fn ($item) => array_merge(...$item), $groupedByDateTime);

        // Ensure all records have all defect report types initialized to 0 if not set
        foreach ($mergedData as &$values) {
            $total = 0;
            foreach ($fields as $type) {
                if (!isset($values[$type])) $values[$type] = 0;
                else $total += $values[$type];
            }
            $values['total'] = $total;
        }
        return $mergedData;
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

    private function getNamesOfModels($params, $keyNames)
    {
        $basicInfoData = [];
        foreach ($keyNames as $value) {
            $modelPath = "App\Models\\" . ucwords($value);
            $nameId = $value . '_id';
            $projectNames = isset($params[$nameId]) ?
                implode(', ', $modelPath::find($params[$nameId])
                    ->pluck('name')
                    ->toArray()) :
                implode(', ', $modelPath::all()
                    ->pluck('name')
                    ->toArray());

            $basicInfoData[$value . "_name"] = $projectNames;
        }
        return $basicInfoData;
    }

    public function getBasicInfoData($params)
    {
        $keyNames = ['project', 'sub_project', 'prod_routing', 'prod_discipline'];
        return $this->getNamesOfModels($params, $keyNames);
    }
}
