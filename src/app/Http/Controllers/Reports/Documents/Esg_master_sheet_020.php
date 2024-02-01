<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Eco_sheet_110;
use App\Http\Controllers\Reports\Reports\Eco_sheet_120;
use App\Http\Controllers\Reports\Reports\Eco_sheet_130;
use App\Http\Controllers\Reports\Reports\Eco_sheet_140;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Project;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Esg_master_sheet_020 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;

    protected $mode = '010';
    protected $projectId = 5;
    protected $type = 'eco_sheet';
    protected $viewName = 'document-esg-master-sheet-020';
    protected $pageLimit = 1000;
    protected $month = '2023-07';

    public function getSqlStr($params)
    {
        [$year, $months, $sqlShowVal, $sqlAgg] =  $this->createValuesForDateParam2($params);
        // dd($year, $months, $sqlShowVal, $sqlAgg);
        $sql = "SELECT
                    tb2.esg_tmpl_id,
                    tb2.esg_tmpl_name,
                    tb2.esg_metric_type_id,
                    tb2.esg_metric_type_name,
                    tb2.unit_name,
                    tb2.esg_state_name,
                    tb2.workplace_id,
                    tb2.workplace_name,
                    tb2.year_num,
                    $sqlShowVal,
                    SUM($sqlAgg) AS total_all_months
                FROM
                        (SELECT
                    tb1.esg_tmpl_id,
                    tb1.esg_tmpl_name,
                    tb1.esg_metric_type_id,
                    tb1.esg_metric_type_name,
                    tb1.workplace_id,
                    tb1.workplace_name,
                    SUBSTR(tb1.esg_month, 1, 4) AS year_num,
                    SUBSTR(tb1.esg_month, 6,7) AS month,
                    tb1.unit_name,
                    tb1.esg_state_name,
                    SUM(tb1.esg_sheet_line_value) AS value_per_month
                    FROM(SELECT
                            SUBSTR(esgms.esg_month,1,7) AS esg_month,
                            esgs.id AS esg_sheet_id,
                            esgs.name AS esg_sheet_name,
                            esgs.id AS sheet_id,
                            esgt.id AS esg_tmpl_id, 
                            esgt.name AS esg_tmpl_name,
                            esgm.id AS esg_metric_type_id,
                            esgm.name AS esg_metric_type_name,
                            esgl.id AS esg_sheet_line_id,
                            esgl.value AS esg_sheet_line_value,
                            esgm.unit AS unit,
                            te.name AS unit_name,
                            te1.name AS esg_state_name,
                            #esgs.esg_date AS esg_date,
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
                            AND esgm.deleted_by IS NULL
                            AND SUBSTR(esgms.esg_month,1,4) = $year";
        #AND SUBSTR(esgms.esg_month,1,4) = '2023'
        #AND esgt.id IN (3)
        #AND esgm.id IN (8)
        #AND wp.id IN (5)";
        #if (Report::checkParam($params, 'ESG_tmpl_id')) $sql .= "\n AND esgt.id IN ({{ESG_tmpl_id}})";
        #if (Report::checkParam($params, 'ESG_metric_type_id')) $sql .= "\n AND esgm.id IN ({{ESG_metric_type_id}})";
        #if (Report::checkParam($params, 'workplace_id')) $sql .= "\n AND wp.id IN ({{workplace_id}})";


        $sql .= "\n) AS tb1
                            GROUP BY year_num, month, workplace_id,esg_tmpl_id, esg_metric_type_id
                ) AS tb2
                GROUP BY
                    tb2.year_num,
                    tb2.workplace_id,
                    tb2.esg_tmpl_id,
                    tb2.esg_metric_type_id";
        return $sql;
    }

    // private function getDataRowSpan($params)
    // {
    //     $sql = "SELECT
    //                 tb2.*,
    //                 SUM(tb2.count_total_12_mons) OVER (PARTITION BY tb2.esg_tmpl_id) AS count_line
    //             FROM (
    //                 SELECT 
    //                     tb1.esg_tmpl_id,
    //                     #tb1.esg_tmpl_name,
    //                     tb1.esg_metric_type_id,
    //                     #tb1.esg_metric_type_name,
    //                     COUNT(DISTINCT tb1.workplace_id) AS count_esg_metric_type,
    //                     COUNT(DISTINCT tb1.workplace_id) AS count_workplace,
    //                     COUNT(DISTINCT tb1.workplace_id) + 1 AS count_total_12_mons,
    //                     GROUP_CONCAT(DISTINCT tb1.workplace_id) AS group_concat_workplace_id
    //                 FROM (
    //                     SELECT
    //                         esgt.id AS esg_tmpl_id, 
    //                         esgt.name AS esg_tmpl_name, 
    //                         esgm.id AS esg_metric_type_id,
    //                         esgm.name AS esg_metric_type_name,
    //                         wp.id AS workplace_id
    //                         FROM esg_sheet_lines esgl
    //                         LEFT JOIN esg_tmpls esgt ON esgt.id = esgl.esg_tmpl_id
    //                         LEFT JOIN esg_sheets esgs ON esgs.id = esgl.esg_sheet_id AND esgt.id = esgs.esg_tmpl_id
    //                         LEFT JOIN esg_metric_types esgm ON esgm.id = esgl.esg_metric_type_id AND esgm.esg_tmpl_id = esgt.id
    //                         LEFT JOIN esg_tmpl_lines esgtl ON esgtl.esg_metric_type_id = esgm.id AND esgtl.esg_tmpl_id = esgt.id
    //                         LEFT JOIN esg_master_sheets esgms ON esgms.id = esgs.esg_master_sheet_id AND esgms.esg_tmpl_id = esgt.id
    //                         LEFT JOIn terms te ON te.id = esgm.unit
    //                         LEFT JOIN terms te1 ON te1.id = esgm.esg_state
    //                         LEFT JOIN workplaces wp ON wp.id = esgms.workplace_id
    //                         WHERE 1 = 1
    //                         AND esgl.deleted_by IS NULL
    //                         AND esgs.deleted_by IS NULL
    //                         AND esgm.deleted_by IS NULL
    //                         AND SUBSTR(esgms.esg_month,1,4) = '2023'
    //                         #AND SUBSTR(esgms.esg_month,1,7) = '2021-05'
    //                         #AND esgt.id IN (3)
    //                         #AND esgm.id IN (8)
    //                         #AND wp.id IN (5)
    //                     ) AS tb1
    //                 GROUP BY tb1.esg_tmpl_id, tb1.esg_metric_type_id
    //             ) AS tb2";
    //     $sqlData = DB::select(DB::raw($sql));
    //     $collection = collect($sqlData);
    //     return $collection;
    // }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Year',
                'dataIndex' => 'year',
            ],
            [
                'title' => 'Half Year',
                'dataIndex' => 'half_year',
                'hasListenTo' => true,
                'allowClear' => true,
            ],
            [
                'title' => 'Month',
                'dataIndex' => 'only_month',
                'allowClear' => true,
                'multiple' => true,
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'workplace_id',
                'multiple' => true,
            ],
            [
                'title' => 'Template',
                'dataIndex' => 'ESG_tmpl_id',
                'multiple' => true,
            ],
            [
                'title' => 'Metric Type',
                'dataIndex' => 'ESG_metric_type_id',
                'multiple' => true,
            ],
        ];
    }

    // private function getRowSpan($params)
    // {
    //     $data = $this->getDataRowSpan($params);
    //     $data = Report::convertToType($data);
    //     $grEsgTmplIds = Report::groupArrayByKey($data, 'esg_tmpl_id');
    //     $grEsgTmplId = array_map(fn ($item) => Report::groupArrayByKey($item, 'esg_metric_type_id'), $grEsgTmplIds);
    //     return $grEsgTmplId;
    // }

    private function groupDataWorkplaces($data)
    {
        $results = [];
        foreach ($data as $key => $value) {
            $value = reset($value);
            $x1 = [$value['total_all_months']];
            $x12 = [];
            foreach (range(1, 12) as $num) {
                if (isset($value[str_pad($num, 2, '0', STR_PAD_LEFT)])) {
                    $x12[] = $value[str_pad($num, 2, '0', STR_PAD_LEFT)];
                } else break;
            }
            $results[$key] = array_merge($x1, $x12);
        }
        return $results;
    }

    private function calculatePerMonth($data)
    {
        $results = [];
        foreach ($data as $key => $value) {
            $value = reset($value);
            $x1 = [$value['total_all_months']];
            $x12 = [];
            foreach (range(1, 12) as $num) {
                if (isset($value[str_pad($num, 2, '0', STR_PAD_LEFT)])) {
                    $x12[] = $value[str_pad($num, 2, '0', STR_PAD_LEFT)];
                } else break;
            }
            $results[$key] = array_merge($x1, $x12);
        }
        return $results;
    }

    private function calculateMonthlyTotals($data)
    {
        $monthlyTotals = [
            'total_all_months' => 0,
            '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0,
            '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0,
            '11' => 0, '12' => 0
        ];

        foreach ($data as $group) {
            foreach ($group as $item) {
                $monthlyTotals['total_all_months'] += $item['total_all_months'];
                for ($month = 1; $month <= 12; $month++) {
                    $monthKey = sprintf('%02d', $month); // Formats the month to a two-digit number
                    if (isset($item[$monthKey])) {
                        $monthlyTotals[$monthKey] += $item[$monthKey];
                    } else break;
                }
            }
        }
        return $monthlyTotals;
    }


    public function changeDataSource($dataSource, $params)
    {
        $dataSource = Report::convertToType($dataSource);
        $grEsgTmplIds = Report::groupArrayByKey($dataSource, 'esg_tmpl_id');
        $esgMeTypes = array_map(fn ($item) => Report::groupArrayByKey($item, 'esg_metric_type_id'), $grEsgTmplIds);
        $results = [];
        foreach ($esgMeTypes as $tmplId => $items) {
            $results[$tmplId] = [
                "rowspan" => 0,
                "esg_tmpl_id" => $tmplId,
                "esg_tmpl_name" => "",
            ];
            $step = 0;
            $numOfLines = 0;
            foreach ($items as $meTypeId => $item) {
                $firstItem = reset($item);
                $esgTmplName = $firstItem['esg_tmpl_name'];
                $esgMetricTypeId = $firstItem['esg_metric_type_id'];
                $esgMetricTypeName = $firstItem['esg_metric_type_name'];
                $unit = $firstItem['unit_name'];
                $state = $firstItem['esg_state_name'];

                if (!$step) {
                    $results[$tmplId] = [
                        "rowspan" => 0,
                        "esg_tmpl_id" => $tmplId,
                        "esg_tmpl_name" => $esgTmplName,
                    ];
                    $step = 1;
                }

                $grWorkplaceIds = Report::groupArrayByKey($item, 'workplace_id');
                $rowspanMetricType = count($item) + 1;
                $rowspanChildren = $rowspanMetricType + 1;
                $numOfLines += count($item) + 1;
                $calculatedNumbers = $this->groupDataWorkplaces($grWorkplaceIds);
                $totalPerMonth = array_values($this->calculateMonthlyTotals($grWorkplaceIds));

                $results[$tmplId]["array_metric_type"][] = [
                    "esg_tmpl_name" => $esgTmplName,
                    "rowspan_metric_type" => $rowspanMetricType,
                    "rowspan_children" => $rowspanChildren,
                    "esg_metric_type_id" => $esgMetricTypeId,
                    "esg_metric_type_name" => $esgMetricTypeName,
                    "unit" => $unit,
                    "state" => $state,
                    "calculated_numbers" => $calculatedNumbers,
                    "total_per_month" => $totalPerMonth
                ];
            }
            $results[$tmplId]["rowspan"] = $numOfLines;
            // dd($numOfLines);
            // dd($results);
        }
        $newDataSource = array_values($results);
        return $newDataSource;
    }
}
