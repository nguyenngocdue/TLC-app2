<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDataModesReport;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Models\User;
use App\Models\Workplace;
use App\Utils\Support\Report;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Hse_incident_report_010 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitDataModesReport;
    protected $maxH = 50;
    protected $mode = '010';
    // protected $rotate45Width = 300;
    protected $libPivotFilters;

    public function getSqlStr($modeParams)
    {
        $dbWorkplaceIds = DB::table('workplaces')->pluck('id')->toArray();
        $currentYear = date('Y');
        $workplaceIds = isset($modeParams['many_workplace_id']) ? $modeParams['many_workplace_id'] : $dbWorkplaceIds;
        $strWorkplaceIds = implode(',', $workplaceIds);
        $year = isset($modeParams['year']) ? $modeParams['year'] : $currentYear;

        $sql = " 
        WITH temp_work_areas AS (
                    SELECT wa.id
                    FROM work_areas wa
                    INNER JOIN workplaces wp ON wa.workplace_id = wp.id
                    WHERE wp.id IN (" . $strWorkplaceIds . ")

                )
                SELECT  
                        SUBSTR(t1.hse_month, 1, 4) AS year,
                        t1.hse_month AS month,
                        t1.*, t2.*, t3.*, t4.*, t5.*, t6.*, t7.*, wp_ids.wp_id_list
                FROM (
                    SELECT
                        SUBSTR(hseir.issue_datetime, 1, 7) AS hse_month,
                        COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 118 THEN hseir.id END) AS hseir_ltc_count_vote,
                        COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 119 THEN hseir.id END) AS hseir_rwc_count_vote,
                        COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = 120 THEN hseir.id END) AS hseir_mtc_count_vote,
                        COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = 107 THEN hseir.id END) AS hseir_incident_count_vote,
                        COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = 109 THEN hseir.id END) AS hseir_near_miss_count_vote,
                        SUM(hseir.lost_days) AS hseir_lost_day_count_vote
                    FROM hse_incident_reports hseir
                    WHERE 1 = 1 
                        AND hseir.work_area_id IN (SELECT id FROM temp_work_areas)    	
                        AND SUBSTR(hseir.issue_datetime, 1, 4) = $year
                    GROUP BY SUBSTR(hseir.issue_datetime, 1, 7)
                ) AS t1
                LEFT JOIN (
                    SELECT
                        SUBSTR(hsefa.injury_datetime, 1, 7) AS hse_month,
                        COUNT(hsefa.id) AS hsefa_count_vote
                    FROM hse_first_aids hsefa
                    WHERE 1 = 1 
                        AND hsefa.work_area_id IN (SELECT id FROM temp_work_areas)
                           AND SUBSTR(hsefa.injury_datetime, 1, 4) = $year
                
                    GROUP BY SUBSTR(hsefa.injury_datetime, 1, 7)
                ) AS t2 ON t1.hse_month = t2.hse_month
                LEFT JOIN (
                    SELECT
                        SUBSTR(hseicshts.start_time, 1, 7) AS hse_month,
                        COUNT(hseicshts.id) AS hseicshts_tmpl_sht_count_vote
                    FROM hse_insp_chklst_shts hseicshts
                    WHERE 1 = 1
                        AND hseicshts.work_area_id IN (SELECT id FROM temp_work_areas)
                        AND hseicshts.hse_insp_tmpl_sht_id = 1
                           AND SUBSTR(hseicshts.start_time, 1, 4) = $year
                    GROUP BY hse_month
                ) AS t3 ON t1.hse_month = t3.hse_month
                LEFT JOIN (
                    SELECT
                        SUBSTR(hsew.walkthrough_datetime, 1, 7) AS hse_month,
                        COUNT(hsew.id) AS hsew_count_vote
                    FROM hse_walkthroughs hsew
                    WHERE 1 = 1
                        AND FIND_IN_SET(hsew.workplace_id, $strWorkplaceIds)
                        AND SUBSTR(hsew.walkthrough_datetime, 1, 4) = $year
                
                    GROUP BY hse_month
                ) AS t4 ON t1.hse_month = t4.hse_month
                LEFT JOIN (
                    SELECT
                        SUBSTR(hsew.walkthrough_datetime, 1, 7) AS hse_month,
                        COUNT(DISTINCT CASE WHEN hseca.correctable_type = 'App\\\Models\\\Hse_walkthrough' THEN hseca.id END) AS hseca_line_count
                    FROM hse_walkthroughs hsew
                    JOIN hse_corrective_actions hseca ON hseca.correctable_id = hsew.id
                    WHERE 1 = 1
                        AND FIND_IN_SET( hsew.workplace_id, $strWorkplaceIds)
                        AND SUBSTR(hsew.walkthrough_datetime, 1, 4) =$year
                    GROUP BY hse_month
                ) AS t5 ON t1.hse_month = t5.hse_month
                LEFT JOIN (
                    SELECT
                        SUBSTR(hrt.training_datetime, 1, 7) AS hse_month,
                        COUNT(CASE WHEN hrtl.hr_training_id = 1 THEN hrt.id END) AS hrt_line_count
                    FROM hr_trainings hrt
                    JOIN hr_training_lines hrtl ON hrtl.hr_training_id = hrt.id
                    WHERE 1 = 1
                        AND hrt.training_location_id IN (SELECT id FROM temp_work_areas)
                           AND SUBSTR(hrt.training_datetime, 1, 4) = $year
                
                    GROUP BY hse_month
                ) AS t6 ON t1.hse_month = t6.hse_month
                LEFT JOIN (
                    SELECT
                        SUBSTR(hseem.metric_month, 1, 7) AS hse_month,
                        SUM(hseem.total_discipline) AS discipline,
                        SUM(hseem.total_third_party_insp_audit) AS third_party_inspection_audit,
                        SUM(hseem.total_drill) AS drill
                    FROM hse_extra_metrics hseem
                    WHERE 1 = 1
                        AND FIND_IN_SET(hseem.workplace_id, $strWorkplaceIds)
                           AND SUBSTR(hseem.metric_month, 1, 4) = $year
                    GROUP BY hse_month
                ) AS t7 ON t1.hse_month = t7.hse_month
                JOIN (
                    SELECT GROUP_CONCAT(id) AS wp_id_list
                    FROM workplaces wp
                    WHERE FIND_IN_SET(wp.id, $strWorkplaceIds)
                ) AS wp_ids";
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
                'dataIndex' => 'month',
                'align' => 'center',
                'width' => 250,
            ],
            [
                'title' => 'Work Hours',
                'dataIndex' => 'work_hours',
                'footer' => 'agg_sum',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'LTI',
                'dataIndex' => 'hseir_ltc_count_vote',
                'align' => 'right',
                'footer' => 'agg_sum',
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
                'title' => 'Incident (property damage,oil spills)',
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
                'title' => 'FAC & Medical assistant ',
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
                'title' => 'HSE inspection ',
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
                'title' => 'Disciplines',
                'dataIndex' => 'discipline',
                'align' => 'right',
                'width' => 100,
            ],
            [
                'title' => 'Third party Inspections & audit',
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
                'title' => 'Month',
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
        if (is_object($dataSource)) $dataSource = array_map(fn ($item) => (array)$item, $dataSource->toArray());
        $wp = Workplace::find(2);
        $workHoursOfYear  = [];
        foreach ([2021, 2022, 2023] as $year) {
            $workHoursOfYear[$year] = $wp->getTotalWorkingHoursOfYear($year);
        }
        foreach ($dataSource as &$value) {
            if (isset($value['year'])) {
                $workHours = $workHoursOfYear[$value['year']];
                $value['work_hours'] = $workHours[$value['month']];
                $totalRecIncidentRate = (($value['hseir_ltc_count_vote']
                    + $value['hseir_rwc_count_vote']
                    + $value['hseir_mtc_count_vote']
                    + $value['hseir_incident_count_vote']
                    + $value['hseir_near_miss_count_vote']) * 200000) / $value['work_hours'];
                $value['trir'] = round($totalRecIncidentRate, 3);
            }
        }
        return collect($dataSource);
    }

    protected function tableDataHeader($modeParams, $dataSource)
    {
        if (is_object($dataSource)) $dataSource = $dataSource->items();
        if (!isset($dataSource[0])) return [];

        $index1 = array_search('hseir_rwc_count_vote', array_keys($dataSource[0]));
        $index2 = array_search('trir', array_keys($dataSource[0]));
        $fields = array_keys(array_slice($dataSource[0], $index1 - 1, $index2 - $index1 + 2));
        $dataHeader = [];
        foreach ($dataSource as $values) {
            foreach ($fields as $field) {
                if (!isset($dataHeader[$field])) {
                    $dataHeader[$field] = 0;
                }
                $dataHeader[$field] += $values[$field];
            }
        }
        // dump($dataHeader, $dataSource);
        return ['month' => 'YTD'] + $dataHeader;
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        return $dataSource;
    }
}
