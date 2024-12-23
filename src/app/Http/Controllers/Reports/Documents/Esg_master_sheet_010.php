<?php

namespace App\Http\Controllers\Reports\Documents;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\Report;

class Esg_master_sheet_010 extends Report_ParentDocument2Controller
{

    use TraitForwardModeReport;
    use TraitMenuTitle;
    use TraitParamsSettingReport;

    protected $mode = '010';
    protected $type = 'esg-master-sheet';
    protected $viewName = 'document-esg-master-sheet-010';
    protected $pageLimit = 10000;
    protected $year = '2023';

    public function getSqlStr($params)
    {
        [$year, $months, $sqlShowVal, $sqlAgg] =  $this->createValuesForDateParam2($params);
        $sql = "SELECT
                    tb2.esg_tmpl_id,
                    tb2.esg_tmpl_name,
                    tb2.esg_metric_type_id,
                    SUBSTR(REGEXP_SUBSTR(tb2.esg_metric_type_name, '[0-9]+(\\.[0-9]+)+'),1,5) AS mark_parent,
                    REGEXP_SUBSTR(tb2.esg_metric_type_name, '[0-9]+(\\.[0-9]+)+') AS mark_heading,
                    CONCAT(IF(CHAR_LENGTH(REGEXP_SUBSTR(tb2.esg_metric_type_name, '[0-9]+(\\.[0-9]+)+')) > 5 , 'children', 'parent'),'_', 
                    SUBSTR(REGEXP_SUBSTR(tb2.esg_metric_type_name, '[0-9]+(\\.[0-9]+)+'),1,3)
                          ) AS mark_item,
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
        if (Report::checkParam($params, 'ESG_tmpl_id')) $sql .= "\n AND esgt.id IN ({{ESG_tmpl_id}})";
        if (Report::checkParam($params, 'ESG_metric_type_id')) $sql .= "\n AND esgm.id IN ({{ESG_metric_type_id}})";
        if (Report::checkParam($params, 'workplace_id')) $sql .= "\n AND wp.id IN ({{workplace_id}})";


        $sql .= "\n) AS tb1
                            GROUP BY year_num, month, workplace_id,esg_tmpl_id, esg_metric_type_id
                ) AS tb2
                GROUP BY
                    tb2.year_num,
                    tb2.workplace_id,
                    tb2.esg_tmpl_id,
                    tb2.esg_metric_type_id
                ORDER BY tb2.esg_metric_type_name";
        return $sql;
    }

    protected function getDefaultValueParams($params, $request)
    {
        $params['year'] = $this->year;
        return $params;
    }

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

    private function groupDataWorkplaces($data, $params)
    {
        $minMonth =  min($this->createValuesForDateParam2($params)[1]);
        $maxMonth =  max($this->createValuesForDateParam2($params)[1]);
        $results = [];
        foreach ($data as $key => $value) {
            $value = reset($value);
            $x1 = [number_format($value['total_all_months'], 2)];
            $x12 = [];
            foreach (range($minMonth, $maxMonth) as $num) {
                if (isset($value[str_pad($num, 2, '0', STR_PAD_LEFT)])) {
                    $x12[] = number_format($value[str_pad($num, 2, '0', STR_PAD_LEFT)], 2);
                } else {
                    $x12[] = 0;
                }
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
                };
            }
            $results[$key] = array_merge($x1, $x12);
        }
        return $results;
    }

    private function calculateMonthlyTotals($data, $params)
    {
        $monthlyTotals = ['total_all_months' => 0];
        $minMonth =  min($this->createValuesForDateParam2($params)[1]);
        $maxMonth =  max($this->createValuesForDateParam2($params)[1]);
        foreach ($data as $group) {
            foreach ($group as $item) {
                $monthlyTotals['total_all_months'] += $item['total_all_months'];
                for ($month = $minMonth; $month <= $maxMonth; $month++) {
                    $monthKey = sprintf('%02d', $month); // Formats the month to a two-digit number
                    if (isset($item[$monthKey])) {
                        if (!isset($monthlyTotals[$monthKey])) {
                            $monthlyTotals[$monthKey] =  0;
                        }
                        $monthlyTotals[$monthKey] += $item[$monthKey];
                    } else {
                        $monthlyTotals[$monthKey] = 0;
                    };
                }
            }
        }
        $monthlyTotals = array_map(fn ($item) => number_format($item, 2), $monthlyTotals);
        return $monthlyTotals;
    }

    private function getChildrenMeTypes($data)
    {
        return array_filter($data, fn ($item) => str_contains($item['mark_item'], 'children'));
    }

    private function getWorkplaceIdsInData($data)
    {
        return array_unique(array_merge(...array_map(function ($item) {
            return array_keys($item['calculated_numbers']);
        }, $data)));
    }

    private function calculateValuesFroWorkplaceIds($workplaceIds, $childrenMeType)
    {
        $results = [];
        foreach ($childrenMeType as $items) {
            $calculatedNumbers = $items['calculated_numbers'];
            foreach ($workplaceIds as $id) {
                $wpDB = $calculatedNumbers[$id];
                if (!isset($results[$id])) {
                    $results[$id] = $wpDB;
                } else {
                    $wpDB = ArrayReport::sumValuesOfArray($results[$id], $wpDB);
                    $results[$id] = $wpDB;
                }
            }
        }
        return $results;
    }

    private function makeDataHaveTotalOthers($dataSource)
    {
        foreach ($dataSource as &$items) {
            $arrayMeType = $items['array_metric_type'];
            $childrenMeType = $this->getChildrenMeTypes($arrayMeType);
            $lineNumber = 0;
            if ($childrenMeType) {
                $workplaceIdsInData = $this->getWorkplaceIdsInData($childrenMeType);
                $totalValueOfWorkplaceIds = $this->calculateValuesFroWorkplaceIds($workplaceIdsInData, $childrenMeType);
                // add rows
                $lineNumber = count($workplaceIdsInData);
                // $items['rowspan'] = $items['rowspan'] + $lineNumber + 1;
                $summaryValueOfWorkplaceIds = ArrayReport::summaryAllValuesOfArray($totalValueOfWorkplaceIds);
                $firstMetric = reset($childrenMeType);
                // add info for others
                $array_others = [
                    "esg_tmpl_name" => $firstMetric['esg_tmpl_name'],
                    "rowspan_metric_type" => $lineNumber + 1,
                    "rowspan_children" => $lineNumber + 1,
                    "esg_metric_type_id" => $firstMetric['esg_metric_type_id'],
                    "esg_metric_type_name" => $firstMetric['mark_parent'] . ".Others",
                    "unit" => $firstMetric['unit'],
                    "state" => $firstMetric['state'],
                    "calculated_numbers" => $totalValueOfWorkplaceIds,
                    "total_per_month" => $summaryValueOfWorkplaceIds
                ];
                $childrenMeType = array_merge([reset($arrayMeType)], [$array_others], $childrenMeType);
                $items['array_metric_type'] = $childrenMeType;

                $newArrayMeType = $items['array_metric_type'];
                $items['rowspan'] =  count($newArrayMeType) * $lineNumber + count($newArrayMeType);
            }
            // dump($items);
        }
        return $dataSource;
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
                $calculatedNumbers = $this->groupDataWorkplaces($grWorkplaceIds, $params);
                $totalPerMonth = array_values($this->calculateMonthlyTotals($grWorkplaceIds, $params));
                $results[$tmplId]["array_metric_type"][] = [
                    "mark_parent" => $firstItem['mark_parent'],
                    "mark_heading" => $firstItem['mark_heading'],
                    "mark_item" => $firstItem['mark_item'],
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
        }
        $newDataSource = array_values($results);

        // stage 2:
        $newDataSource = $this->makeDataHaveTotalOthers($newDataSource);
        // dd($newDataSource);
        return $newDataSource;
    }
}
