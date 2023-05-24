<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Utils\Support\Report;

class Qaqc_ncr_020 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitFunctionsReport;
    use TraitModifyDataToExcelReport;

    protected $project_id = 5;
    protected $sub_project_id = 82;

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT 
            mirtb.*,
            tb2.*,
            COALESCE(_total_open_ncr, 0) AS total_open_ncr,
            COALESCE(_num_closed_ncr, 0) AS num_closed_ncr,
            COALESCE(_num_surplus_ncr, 0) AS num_surplus_ncr,
            COALESCE(ROUND(_num_surplus_ncr * 100 / COALESCE(_total_open_ncr, 1), 0),0) AS open_ratio_ncr,
            COALESCE(ROUND(_num_closed_ncr * 100 / COALESCE(_total_open_ncr, 1), 0),0) AS closed_ratio_ncr       
            FROM 
            (SELECT 
                sp.id AS sub_project_id
                ,sp.project_id AS project_id
                ,sp.name AS sub_project_name
                ,GROUP_CONCAT(mir.id) AS mir_ids
                ,DATE_FORMAT(mir.created_at, '%Y-%m') AS year_month_open_mir
                ,COUNT(DATE_FORMAT(mir.created_at, '%Y-%m')) AS num_open_mir
                FROM  sub_projects sp, qaqc_wirs mir
                WHERE 1 = 1
                    AND sp.deleted_by IS  NULL
                    AND mir.deleted_by IS NULL";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        $sql .= "\n AND sp.id = mir.sub_project_id
                    GROUP BY year_month_open_mir) AS mirtb
                        LEFT JOIN (SELECT
                                        #sub_project_id,
                                        #sub_project_name,
                                        year_month_open_ncr,
                                        GROUP_CONCAT(ids_open_ncr) AS ids_open_ncr,
                                        GROUP_CONCAT(ids_mir_has_ncr) AS ids_mir_has_ncr ,
                                        GROUP_CONCAT(year_month_open_ncr) AS all_year_month_ncr,
                                    
                                        GROUP_CONCAT(ids_mir) AS ids_mir,
                                        GROUP_CONCAT(ncr_status) AS ncr_status ,
                                        COALESCE(COUNT(year_month_open_ncr), 0) AS _total_open_ncr,
                                        COUNT(CASE WHEN ncr_closed = 'closed' THEN 1 END) AS _num_closed_ncr,
                                        COALESCE((COUNT(year_month_open_ncr) - COUNT(CASE WHEN ncr_closed = 'closed' THEN 1 END)), 0) AS _num_surplus_ncr
                                    FROM (
                                        SELECT
                                            sp.id AS sub_project_id,
                                            sp.name AS sub_project_name,
                                            DATE_FORMAT(ncr.created_at, '%Y-%m') AS year_month_open_ncr,
                                            ncr.id AS ids_open_ncr,
                                            ncr.parent_id AS ids_mir_has_ncr,
                                            mir.id AS ids_mir,
                                            IF(DATE_FORMAT(ncr.created_at, '%Y-%m') = DATE_FORMAT(ncr.closed_at, '%Y-%m') AND ncr.status = 'closed', 'closed', 'Open') AS ncr_closed,
                                            ncr.status AS ncr_status
                                        FROM qaqc_ncrs ncr, sub_projects sp, qaqc_mirs mir
                                        WHERE 1 = 1
                                            AND ncr.deleted_by IS NULL
                                            AND sp.deleted_by IS  NULL
                                            AND mir.deleted_by IS NULL";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND ncr.sub_project_id = '{{sub_project_id}}'";
        $sql .= "\n  AND mir.id = ncr.parent_id
                    AND ncr.sub_project_id = sp.id
                    AND ncr.sub_project_id = mir.sub_project_id
                    AND ncr.parent_type = 'App\\\Models\\\Qaqc_mir'
                    AND DATE_FORMAT(ncr.created_at, '%Y-%m') = DATE_FORMAT(mir.created_at, '%Y-%m')
                GROUP BY
                    ncr.created_at,
                    ncr.closed_at,
                    ncr.status,
                    ncr.id,
                    ids_mir_has_ncr,
                    ids_mir) AS tb1
                        GROUP BY
                            year_month_open_ncr) AS tb2 ON tb2.year_month_open_ncr = mirtb.year_month_open_mir
                        ORDER BY
                        mirtb.year_month_open_mir DESC";
        return $sql;
    }

    protected function getTableColumns($dataSource, $modeParams)
    {
        $dataColumns = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "Month",
                "dataIndex" => "year_month_open_mir",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "MIR Requests",
                "dataIndex" => "num_open_mir",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "Total MIR - NCRs",
                "dataIndex" => "total_open_ncr",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "MIR - NCRs (Open)",
                "dataIndex" => "num_surplus_ncr",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "MIR - NCRs (Open Ratio) (%)",
                "dataIndex" => "open_ratio_ncr",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "MIR - NCRs (Closed)",
                "dataIndex" => "num_closed_ncr",
                "align" => "center",
                'width' => 100

            ],
            [
                "title" => "MIR - NCRs (Closed Ratio) (%)",
                "dataIndex" => "closed_ratio_ncr",
                "align" => "center",
                'width' => 100

            ],

            // [
            //     "dataIndex" => "mir_ids",
            //     "align" => "center",
            //     'width' => 100

            // ],
            // [
            //     "dataIndex" => "ids_open_ncr",
            //     "align" => "center",
            //     'width' => 200

            // ],
            // [
            //     "dataIndex" => "all_year_month_ncr",
            //     "align" => "center",
            //     'width' => 300

            // ],
            // [
            //     "dataIndex" => "ncr_status",
            //     "align" => "center",
            //     'width' => 300

            // ],
            [
                "title" => "MIR - NCRs (Open of previous months)",
                "dataIndex" => "previous_months_open_ncr",
                "align" => "center",
                'width' => 300
            ],
            [
                "title" => "MIR - NCRs (Closed of previous months)",
                "dataIndex" => "previous_months_closed_ncr",
                "align" => "center",
                'width' => 300
            ]
        ];
        // $sqlDataCol = $this->createTableColumns($dataSource);

        return  $dataColumns;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Project',
                'dataIndex' => 'project_id',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'allowClear' => false,
            ]
        ];
    }

    private function combineValuesInArrayByKey($data, $item)
    {
        $keysToCombine = [
            "ncr_status",
            "all_year_month_ncr",
            "ids_open_ncr"
        ];
        $combinedValues = array_fill_keys($keysToCombine, "");

        foreach ($data as $key => $value) {
            $date1 = strtotime($item);
            $date2 = strtotime($key);
            if ($date1 > $date2) {
                array_walk($keysToCombine, function ($combineKey) use (&$combinedValues, $value) {
                    $combinedValues[$combineKey] .= $value[$combineKey] . ',';
                });
            }
        }

        $combinedValues = array_map(fn ($item) => trim($item, ','), $combinedValues);
        return $combinedValues;
    }

    private function groupData($data, $month)
    {
        $combinedData = $this->combineValuesInArrayByKey($data, $month);
        $combinedData = array_map(fn ($item) => explode(',', $item), $combinedData);

        $idsNcrClosed = [];
        $idsNcrOpen = [];
        foreach ($combinedData['all_year_month_ncr'] as $key => $currentMonth) {
            if (!$currentMonth) continue;
            $currentNcrStatus = $combinedData['ncr_status'][$key];
            $currentIdsOpenNcr = $combinedData['ids_open_ncr'][$key];
            if ($currentNcrStatus !== 'closed' && $currentIdsOpenNcr) {
                $idsNcrOpen[$currentMonth][] = $currentIdsOpenNcr;
            } else {
                $idsNcrClosed[$currentMonth][] = $currentIdsOpenNcr;
            }
        }

        return [$idsNcrClosed, $idsNcrOpen];
    }


    private function createTable($data, $title)
    {
        if (empty($data)) return "";
        $tableRows = "";
        foreach ($data as $day => $ids) {
            $count = count($ids);
            $tableRows .= "
                <tr>
                    <td>{$day}</td>
                    <td>{$count}</td>
                </tr>";
        }
        $table = "<table class='table-auto whitespace-no-wrap w-full text-sm'>
                    <thead class='sticky z-0 top-0 text-center bg-gray-50'>
                        <tr>
                            <th>Month</th>
                            <th>NCRs Qty</th>
                        </tr>
                    </thead>
                    <tbody class='divide-y bg-white dark:divide-gray-700 dark:bg-gray-800'>
                        {$tableRows}
                    </tbody>
                </table>";

        return $table;
    }

    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        $monthYears = [];
        foreach ($dataSource as $key => $value) {
            $data = [
                'ncr_status' => $value->ncr_status,
                'all_year_month_ncr' => $value->all_year_month_ncr,
                'ids_open_ncr' => $value->ids_open_ncr
            ];
            $monthYears[$value->year_month_open_mir] = $data;
        }
        $dataNcrs = [];
        $savedIdsNcrClosed = [];
        foreach (array_reverse($monthYears) as $item => $value) {
            [$idsNcrClosed, $idsNcrOpen] = $this->groupData($monthYears, $item);
            $idsNcrClosed = array_diff_key($idsNcrClosed, array_flip($savedIdsNcrClosed));
            $dataNcrs[$item] = [
                'open_status' => $idsNcrOpen,
                'closed_status' => $idsNcrClosed
            ];
            $savedIdsNcrClosed = array_merge($savedIdsNcrClosed, array_keys($idsNcrClosed));
        }

        foreach ($dataSource as $key => $value) {
            $dt = $dataNcrs[$value->year_month_open_mir] ?? [];
            if (isset($dt['open_status'])) {
                $dataSource[$key]->previous_months_open_ncr = (object) [
                    'value' => $this->createTable($dt['open_status'], 'Open') ?? ""
                ];
            }
            if (isset($dt['closed_status'])) {
                $dataSource[$key]->previous_months_closed_ncr = (object) [
                    'value' => $this->createTable($dt['closed_status'], 'Closed') ?? ""
                ];
            }
            $dataSource[$key]->total_open_ncr = (object) [
                'value' => $value->total_open_ncr,
                'cell_title' => 'Ids: ' . $value->ids_open_ncr
            ];
            $dataSource[$key]->num_open_mir = (object) [
                'value' => $value->num_open_mir,
                'cell_title' => 'Ids: ' . $value->mir_ids
            ];
        }
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        // dd($modeParams);
        $x = 'project_id';
        $y = 'sub_project_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->project_id;
            $modeParams[$y] = $this->sub_project_id;
        }
        // dd($modeParams);
        return $modeParams;
    }
}
