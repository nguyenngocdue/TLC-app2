<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentRegisterController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Report;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Qaqc_ncr_010 extends Report_ParentRegisterController
{
    use TraitDynamicColumnsTableReport;
    use TraitFunctionsReport;
    use TraitModifyDataToExcelReport;

    protected $project_id = 5;
    protected $sub_project_id = 82;

    public function getSqlStr($modeParams)
    {
        $sql = "SELECT mirtb.*,
		tb2.*,
        ROUND(num_surplus_ncr*100/total_open_ncr,0) AS open_ratio_ncr,
        ROUND(num_closed_ncr*100/total_open_ncr,0) AS closed_ratio_ncr        
            FROM 
            (SELECT 
                sp.id AS sub_project_id
                ,sp.project_id AS project_id
                ,sp.name AS sub_project_name
                ,GROUP_CONCAT(mir.id) AS mir_ids
                ,DATE_FORMAT(mir.created_at, '%Y-%m') AS year_month_open_mir
                ,COUNT(DATE_FORMAT(mir.created_at, '%Y-%m')) AS num_open_mir
                FROM  sub_projects sp, qaqc_mirs mir
                WHERE 1 = 1";
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
                                        COUNT(year_month_open_ncr) AS total_open_ncr,
                                        COUNT(CASE WHEN ncr_closed = 'closed' THEN 1 END) AS num_closed_ncr,
                                        (COUNT(year_month_open_ncr) - COUNT(CASE WHEN ncr_closed = 'closed' THEN 1 END)) AS num_surplus_ncr
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
                                        WHERE 1 = 1";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND ncr.sub_project_id = '{{sub_project_id}}'";
        $sql .= "\n AND mir.id = ncr.parent_id
                                            AND ncr.sub_project_id = sp.id
                                            AND ncr.sub_project_id = mir.sub_project_id
                                            AND ncr.parent_type = 'App\\\Models\\\Qaqc_mir'
                                            AND DATE_FORMAT(ncr.created_at, '%Y-%m') = DATE_FORMAT(mir.created_at, '%Y-%m')
                                        GROUP BY ncr.created_at, ncr.closed_at, ncr.status, ncr.id,ids_mir_has_ncr, ids_mir
                                    ) AS tb1 GROUP BY year_month_open_ncr) AS tb2 ON  tb2.year_month_open_ncr = mirtb.year_month_open_mir
                                    ORDER BY mirtb.year_month_open_mir DESC";
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


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        $monthYears = [];
        foreach ($dataSource as $key => $value) {
            if (is_null($value->ncr_status)) continue;
            $data = [];
            $data['ncr_status'] = $value->ncr_status;
            $data['all_year_month_ncr'] = $value->all_year_month_ncr;
            $data['ids_open_ncr'] = $value->ids_open_ncr;
            $monthYears[$value->year_month_open_mir] = $data;
        }

        $dataNcrs = [];
        foreach ($monthYears as $item => $value) {
            [$idsNcrClosed, $idsNcrOpen] = $this->groupData($monthYears, $item);
            $dataNcrs[$item]['open_status'] = $idsNcrOpen;
            $dataNcrs[$item]['closed_status'] = $idsNcrClosed;
        };

        foreach ($dataSource as $key => $value) {
            // dd($value);
            $dt = $dataNcrs[$value->year_month_open_ncr] ?? [];
            if (isset($dt['open_status'])) {
                $dataSource[$key]->previous_months_open_ncr = (object)[
                    'value' => $this->createTable($dt['open_status'], 'Open') ?? ""
                ];
            }
            // dump($dt);
            if (isset($dt['closed_status'])) {
                $dataSource[$key]->previous_months_closed_ncr = (object)[
                    'value' => $this->createTable($dt['closed_status'], 'Closed') ?? ""
                ];
            };
        }
        return collect($dataSource);
    }



    private function compileValuesInArrayByKey($data, $item)
    {
        $array = [
            "ncr_status" => "",
            "all_year_month_ncr" => "",
            "ids_open_ncr" => ""
        ];
        foreach ($data as $key => $value) {
            $date1 = strtotime($item);
            $date2 = strtotime($key);
            if (is_null($value['ncr_status'])) continue;
            if ($date1 > $date2) {
                $array['ncr_status'] .= $value['ncr_status'] . ',';
                $array['all_year_month_ncr'] .= $value['all_year_month_ncr'] . ',';
                $array['ids_open_ncr'] .= $value['ids_open_ncr'] . ',';
            }
        }
        $array = array_map(fn ($item) => trim($item, ','), $array);
        return $array;
    }
    private function groupData($data, $item)
    {
        $data = $this->compileValuesInArrayByKey($data, $item);
        $data = array_map(fn ($i) => explode(',', $i), $data);

        $ncr_status = $data['ncr_status'];
        $all_year_month_ncr = $data['all_year_month_ncr'];
        $ids_open_ncr = $data['ids_open_ncr'];

        $idsNcrClosed = [];
        $idsNcrOpen = [];
        foreach ($all_year_month_ncr as $key => $month) {
            if (!$month) continue;
            if ($ncr_status[$key] !== 'closed' && $ids_open_ncr[$key]) {
                $idsNcrOpen[$month][] =   $ids_open_ncr[$key];
            } else {
                $idsNcrClosed[$month][] = $ids_open_ncr[$key];
            }
        }
        return [$idsNcrClosed, $idsNcrOpen];
    }

    private function createTable($data, $title)
    {

        if (empty($data)) return "";
        $str1 = "";
        foreach ($data as $day => $ids) {
            $count = count($ids);
            $str1 .= "
                <tr>
                    <td>{$day}</td>
                    <td>{$count}</td>
                </tr>";
        }
        $str2 = "<table class='table-auto whitespace-no-wrap w-full text-sm'>
                    <thead class='sticky z-0 top-0 text-center bg-gray-50'>
                    <tr>
                        <th>Month</th>
                        <th>NCRs QTT</th>
                    </tr>
                    </thead>
                    <tbody class='divide-y bg-white dark:divide-gray-700 dark:bg-gray-800'>
                        {$str1}
                    </tbody>
                </table>";
        return $str2;
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
