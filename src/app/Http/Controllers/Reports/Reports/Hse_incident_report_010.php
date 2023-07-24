<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Models\Workplace;
use App\Utils\Support\Report;
use App\Utils\Support\StringPivotTable;
use Illuminate\Support\Facades\DB;

class Hse_incident_report_010 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;

    protected $maxH = 80;
    protected $year = 2023;
    #protected $many_workplace_id = [2,4];

    public function getSqlStr($modeParams)
    {
        $dbWorkplaceIds = DB::table('workplaces')->pluck('id')->toArray();
        $currentYear = date('Y');

        $workplaceIds = isset($modeParams['many_workplace_id']) && $modeParams['many_workplace_id'][0] ? $modeParams['many_workplace_id'] : $dbWorkplaceIds;
        $strWorkplaceIds = '(' . implode(',', $workplaceIds) . ')';

        $year = isset($modeParams['year']) ? $modeParams['year'] : $currentYear;

        $sql = "WITH temp_work_areas AS (
                        SELECT wa.id
                        FROM work_areas wa
                        INNER JOIN workplaces wp ON wa.workplace_id = wp.id
                        WHERE wp.id IN $strWorkplaceIds
                    ),
                    t1 AS (
                        SELECT
                            SUBSTR(hseir.issue_datetime, 1, 7) AS hse_month,
                            NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 118 AND hseir.status != 'new' THEN hseir.id END), 0) AS hseir_ltc_count_vote,
                            NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 119 AND hseir.status != 'new' THEN hseir.id END), 0) AS hseir_rwc_count_vote,
                            NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 120 AND hseir.status != 'new' THEN hseir.id END), 0) AS hseir_mtc_count_vote,
                            NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = 107 AND hseir.status != 'new' AND hseir.incident_doc_sub_type_id IN (112,114) THEN hseir.id END), 0) AS hseir_incident_count_vote,
                            NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = 109 AND hseir.status != 'new' THEN hseir.id END), 0) AS hseir_near_miss_count_vote,
                            NULLIF(SUM(CASE WHEN hseir.status != 'new' THEN hseir.lost_days END), 0) AS hseir_lost_day_count_vote
                        FROM hse_incident_reports hseir
                        WHERE 1 = 1 
                            AND hseir.work_area_id IN (SELECT id FROM temp_work_areas)  
                            AND SUBSTR(hseir.issue_datetime, 1, 4) = $year
                        GROUP BY SUBSTR(hseir.issue_datetime, 1, 7)
                    ),
                    t2 AS (
                        SELECT
                                            SUBSTR(hsefa.injury_datetime, 1, 7) AS hse_month,
                                            COUNT(CASE WHEN 1 = 1 THEN hsefa.id END) AS hsefa_count_vote
                                        FROM hse_first_aids hsefa
                                        WHERE 1 = 1 
                                            AND hsefa.work_area_id IN (SELECT id FROM temp_work_areas)  
                                            AND SUBSTR(hsefa.injury_datetime, 1, 4) = $year
                                            AND hsefa.status = 'active'
                                        GROUP BY SUBSTR(hsefa.injury_datetime, 1, 7)
                    ),
                    t3 AS (
                        SELECT
                                            SUBSTR(hseicshts.start_time, 1, 7) AS hse_month,
                                            COUNT(hseicshts.id) AS hseicshts_tmpl_sht_count_vote
                                        FROM hse_insp_chklst_shts hseicshts
                                        WHERE 1 = 1
                                            AND hseicshts.workplace_id IN $strWorkplaceIds
                                            AND hseicshts.hse_insp_tmpl_sht_id = 1
                                            AND SUBSTR(hseicshts.start_time, 1, 4) = $year
                                            AND hseicshts.status = 'active'
                                        GROUP BY hse_month
                    ),
                    t4 AS (
                                        SELECT
                                            SUBSTR(hsew.walkthrough_datetime, 1, 7) AS hse_month,
                                            COUNT(hsew.id) AS hsew_count_vote
                                        FROM hse_walkthroughs hsew
                                        WHERE 1 = 1
                                            AND hsew.workplace_id IN $strWorkplaceIds
                                            AND SUBSTR(hsew.walkthrough_datetime, 1, 4) = $year
                                            AND hsew.status = 'closed'
                                        GROUP BY hse_month
                    ),
                    t5 AS (
                        SELECT
                                            SUBSTR(hsew.walkthrough_datetime, 1, 7) AS hse_month,
                                            #COUNT(DISTINCT CASE WHEN hsew.status = 'closed' THEN hsew.id END)hsew_count_test,
                                            NULLIF(COUNT(DISTINCT CASE WHEN hseca.correctable_type = 'App\\\\Models\\\Hse_walkthrough' 
                                                                OR hseca.correctable_type ='App\\\\Models\\\\Hse_insp_chklst_line' THEN hseca.id END),0) AS hseca_line_count
                                        FROM hse_walkthroughs hsew
                                        JOIN hse_corrective_actions hseca ON hseca.correctable_id = hsew.id
                                        WHERE 1 = 1
                                            AND hsew.workplace_id IN $strWorkplaceIds
                                            AND SUBSTR(hsew.walkthrough_datetime, 1, 4) = $year
                                            AND SUBSTR(hseca.opened_at, 1, 7) = SUBSTR(hsew.walkthrough_datetime, 1, 7)
                                        GROUP BY hse_month
                    ),
                    t6 AS (
                                        SELECT
                                            SUBSTR(hrt.training_datetime, 1, 7) AS hse_month,
                                            NULLIF(COUNT(CASE WHEN hrtl.hr_training_id = 1 AND hrt.status = 'closed' THEN hrt.id END),0) AS hrt_line_count
                                        FROM hr_trainings hrt
                                        JOIN hr_training_lines hrtl ON hrtl.hr_training_id = hrt.id
                                        WHERE 1 = 1
                                            AND hrt.training_location_id IN (SELECT id FROM temp_work_areas)
                                            AND SUBSTR(hrt.training_datetime, 1, 4) = $year
                                        GROUP BY hse_month
                    ),
                    t7 AS (
                        SELECT
                            SUBSTR(hseem.metric_month, 1, 7) AS hse_month,
                            MAX(hseem.total_discipline) AS discipline,
                            MAX(hseem.total_third_party_insp_audit) AS third_party_inspection_audit,
                            MAX(hseem.total_drill) AS drill,
                            MAX(hseem.total_meeting_toolbox) AS total_meeting_toolbox
                        FROM hse_extra_metrics hseem
                        WHERE hseem.workplace_id IN $strWorkplaceIds
                            AND SUBSTR(hseem.metric_month, 1, 4) = $year
                            AND hseem.status = 'active'
                        GROUP BY hse_month
                    ),
                    wp_ids AS (
                        SELECT GROUP_CONCAT(id) AS wp_id_list
                        FROM workplaces wp
                        WHERE wp.id IN (5,6,1,2,3,4)
                    )
                    
                    SELECT  
                        SUBSTR(t1.hse_month, 1, 4) AS year,
                        t1.hse_month AS month,
                        t1.*,t2.*,t3.*,t4.*, t5.*, t6.*, t7.*, wp_ids.wp_id_list
                    FROM t1
                    LEFT JOIN t2 ON t1.hse_month = t2.hse_month
                    LEFT JOIN t3 ON t1.hse_month = t3.hse_month
                    LEFT JOIN t4 ON t1.hse_month = t4.hse_month
                    LEFT JOIN t5 ON t1.hse_month = t5.hse_month
                    LEFT JOIN t6 ON t1.hse_month = t6.hse_month
                    LEFT JOIN t7 ON t1.hse_month = t7.hse_month
                    CROSS JOIN wp_ids
                    UNION
                    SELECT  
                        SUBSTR(t1.hse_month, 1, 4) AS year,
                        t1.hse_month AS month,
                        t1.*,t2.*,t3.*,t4.*, t5.*,t6.*, t7.*, wp_ids.wp_id_list
                    FROM t1
                    RIGHT JOIN t2 ON t1.hse_month = t2.hse_month
                    RIGHT JOIN t3 ON t1.hse_month = t3.hse_month
                    RIGHT JOIN t4 ON t1.hse_month = t4.hse_month
                    RIGHT JOIN t5 ON t1.hse_month = t5.hse_month
                    RIGHT JOIN t6 ON t1.hse_month = t6.hse_month
                    RIGHT JOIN t7 ON t1.hse_month = t7.hse_month 
                    CROSS JOIN wp_ids";
        return $sql;
    }

    protected function getTableColumns($dataSource, $modeParams)
    {
        // if (is_object($dataSource)) $dataSource = array_map(fn ($item) => (array)$item, $dataSource->items());
        // $dataColumn = [];
        // foreach ($dataSource[0] as $key => $value) {
        //     $dataColumn[] = [
        //         'dataIndex' => $key
        //     ];
        // }
        // dd($dataSource);
        $dataColumn = [
            [
                'title' => 'Month',
                'dataIndex' => 'hse_month',
                'align' => 'center',
                'width' => 250,
            ],
            [
                'title' => 'Work Hours',
                'dataIndex' => 'work_hours',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'LTI',
                'dataIndex' => 'hseir_ltc_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'RWC',
                'dataIndex' => 'hseir_rwc_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'MTC',
                'dataIndex' => 'hseir_mtc_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Incident (Property damage,Oil spills)',
                'dataIndex' => 'hseir_incident_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Near Miss',
                'dataIndex' => 'hseir_near_miss_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'FAC & Medical Assistant ',
                'dataIndex' => 'hsefa_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Lost Days',
                'dataIndex' => 'hseir_lost_day_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'HSE Inspection ',
                'dataIndex' => 'hseicshts_tmpl_sht_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'HSE Walkthrough',
                'dataIndex' => 'hsew_count_vote',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'HSE Observations',
                'dataIndex' => 'hseca_line_count',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'HSE Training & Induction (Pax)',
                'dataIndex' => 'hrt_line_count',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'HSE Meeting Toolbox & Committee ',
                'dataIndex' => 'total_meeting_toolbox',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Disciplines',
                'dataIndex' => 'discipline',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Third party Inspections & Audit',
                'dataIndex' => 'third_party_inspection_audit',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Drills',
                'dataIndex' => 'drill',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'TRIR',
                'dataIndex' => 'trir',
                'align' => 'right',
                'width' => 100,
            ],
        ];

        return $dataColumn;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Year',
                'dataIndex' => 'year',
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'many_workplace_id',
                'allowClear' => true,
                'multiple' => true,
            ],
        ];
    }



    protected function transformDataSource($dataSource, $modeParams)
    {
        // dump($dataSource);
        if (is_object($dataSource)) $dataSource = array_map(fn ($item) => (array)$item, $dataSource->toArray());
        if (empty($dataSource)) return collect([]);

        $dbWorkplaceIds = DB::table('workplaces')->pluck('id')->toArray();
        $workplaceIds = isset($modeParams['many_workplace_id']) &&  $modeParams['many_workplace_id'][0] ? $modeParams['many_workplace_id'] : $dbWorkplaceIds;
        $workPlacesHoursOfYear  = [];
        foreach ($workplaceIds as $workplaceId) {
            $wp = Workplace::find($workplaceId);
            foreach ([2021, 2022, 2023] as $year) {
                $workPlacesHoursOfYear[$year][] = $wp->getTotalWorkingHoursOfYear($year);
            }
        }
        
        $workHoursOfYear = Report::sumAndMergeNestedKeys($workPlacesHoursOfYear);
        $data = [];
        $hseMonths = [];
        foreach ($dataSource as $value) {
            $hseMonth = is_null($value['month']) ? $value['hse_month'] : $value['month'];
            if ($hseMonth) {
                [$year, $month] = explode('-', $hseMonth);
                if (!isset($workHoursOfYear[$year])) {
                    $value['work_hours'] = null;
                    $value['trir'] = null;
                    $value['year'] = $year;
                    $value['hse_month'] = $hseMonth;
        
                    if (!in_array($hseMonth, $hseMonths)) $data[] = $value;
                    $hseMonths[] = $hseMonth;
                    continue;
                }
                $workHours = $workHoursOfYear[$year];
                if (!isset($workHours[$hseMonth])) {
                    // Set work_hours and trir values to null
                    $value['work_hours'] = null;
                    $value['trir'] = null;
                    $value['year'] = $year;
                    $value['hse_month'] = $hseMonth;
        
                    if (!in_array($hseMonth, $hseMonths)) $data[] = $value;
                    $hseMonths[] = $hseMonth;
                    continue;
                }
                $value['work_hours'] = $workHours[$hseMonth];
                $value['hse_month'] = $hseMonth;
                // Calculate the total recordable incident rate (TRIR)
                $totalRecIncidentRate = (($value['hseir_ltc_count_vote']
                    + $value['hseir_rwc_count_vote']
                    + $value['hseir_mtc_count_vote']
                    + $value['hseir_incident_count_vote']
                    + $value['hseir_near_miss_count_vote']) * 200000) / $value['work_hours'];
                $value['trir'] = ($num = round($totalRecIncidentRate, 2)) ? $num : null;
                if (!in_array($hseMonth, $hseMonths)) $data[] = $value;
                $hseMonths[] = $hseMonth;
            }
        }
        $dataSource = $data;
        
        $months = array_column($dataSource, 'hse_month');
        $addMissingMonths = Report::addMissingMonths($months);
        $diffMonths = array_diff($addMissingMonths, $months);
        
        $keysInDataSource = array_keys($dataSource[0]);
        
        $data2 =  array_map(function ($item) use ($keysInDataSource) {
            $arr = array_fill_keys($keysInDataSource, null);
            $arr['month'] = $item;
            $arr['year'] = substr($item, 0, 4);
            $arr['hse_month'] = $item;
            return $arr;
        }, $diffMonths);
        
        $dataSource = array_merge($dataSource, $data2);
        // dump($dataSource);

        // Create a new line at the end of the table
        $fields = array_keys($dataSource[0]);
        $dataFooter = [];
        foreach ($dataSource as $values) {
            foreach ($fields as $field) {
                try {
                    if (!isset($dataFooter[$field])) {
                        $dataFooter[$field] = 0;
                    }
                    $dataFooter[$field] += $values[$field];
                } catch (\Exception $e) {
                    $dataFooter[$field] = $values[$field];
                }
            }
        }
        $dataSource = Report::sortByKey($dataSource, 'hse_month');
        $dataFooter['year'] = null;
        $dataFooter['hse_month'] = 'YTD';
        $dataFooter['trir'] = round((($dataFooter['hseir_ltc_count_vote']
            + $dataFooter['hseir_rwc_count_vote']
            + $dataFooter['hseir_mtc_count_vote']
            + $dataFooter['hseir_incident_count_vote']
            + $dataFooter['hseir_near_miss_count_vote']) * 200000) / $dataFooter['work_hours'], 2);
        $dataSource = array_merge($dataSource, [$dataFooter]);

        // dd($dataSource);
        return collect($dataSource);
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        foreach ($dataSource as $key => $values) {
            if (isset($values['trir'])) {
                $values['trir'] = (object) [
                    'value' => $values['trir'],
                    'cell_title' => 'SUM(LTI, RWC ,MTC ,Incident (property damage,oil spills) ,Near Miss)*200000/Work Hours)'
                ];
                $values['work_hours'] = round($values['work_hours'] ? $values['work_hours'] : 0, 2);
                $dataSource[$key] = $values;
            }
        }
        $lastElement = array_slice($dataSource->toArray(), count($dataSource) - 1);
        foreach ($lastElement as &$values) {
            foreach ($values as $key => &$value) {
                if (is_object($value)) $value = $value->value;
                $values[$key] = (object)[
                    'value' => $value,
                    'cell_class' => 'bg-gray-300 font-bold',
                ];
            }
        }
        $dataSource = array_merge(array_slice($dataSource->toArray(), 0, count($dataSource) - 1), $lastElement);
        // dd($dataSource);
        return collect($dataSource);
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $x = 'year';
        $y = 'many_workplace_id';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$x] = $this->year;
            #$modeParams[$y] = $this->many_workplace_id;
        }
        return $modeParams;
    }
}
