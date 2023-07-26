<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLegendReport;
use App\Models\Workplace;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Hse_incident_report_010 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;

    protected $maxH = 80;
    protected $year = 2023;
    protected $tableTrueWidth = false;
    protected $pageLimit = 100;

    public function getSqlStr($modeParams)
    {
        // alias variable in SQL
        $lti = 118;
        $rwc = 119;
        $mtc = 120;
        $incident = 107;
        $incidentProperty = 112;
        $incidentOil = 114;
        $nearMiss = 109;

        $exceptStatusLti = 'new';
        $exceptStatusRwc = 'new';
        $exceptStatusMtc = 'new';
        $exceptStatusIncident = 'new';
        $exceptStatusNearMiss = 'new';
        $exceptStatusLostDay = 'new';

        $statusFirstAid = 'active';
        $statusICShts = 'active';
        $statusWalkthrough = 'closed';
        $statusHrTraining = 'closed';
        $statusExtraMetric = 'active';
        
        //params from user settings 
        $dbWorkplaceIds = DB::table('workplaces')->pluck('id')->toArray();
        $currentYear = date('Y');
        $workplaceIds = isset($modeParams['many_workplace_id']) && $modeParams['many_workplace_id'][0] ? $modeParams['many_workplace_id'] : $dbWorkplaceIds;
        $strWorkplaceIds = '(' . implode(',', $workplaceIds) . ')';
        $year = isset($modeParams['year']) ? $modeParams['year'] : $currentYear;
        // String SQL query
        $sql = "WITH temp_work_areas AS (
                    SELECT wa.id
                    FROM work_areas wa
                    INNER JOIN workplaces wp ON wa.workplace_id = wp.id
                    WHERE wp.id IN $strWorkplaceIds
                ),
                t1 AS (
                    SELECT
                        SUBSTR(hseir.issue_datetime, 1, 7) AS hse_month,
                        NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = $lti AND hseir.status != '$exceptStatusLti' THEN hseir.id END), 0) AS hseir_ltc_count_vote, 
                        NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = $rwc AND hseir.status != '$exceptStatusRwc' THEN hseir.id END), 0) AS hseir_rwc_count_vote, 
                        NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_sub_type_id = $mtc AND hseir.status != '$exceptStatusMtc' THEN hseir.id END), 0) AS hseir_mtc_count_vote, 
                        NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = $incident AND hseir.status != '$exceptStatusIncident' AND hseir.incident_doc_sub_type_id IN ($incidentProperty, $incidentOil) THEN hseir.id END), 0) AS hseir_incident_count_vote, 
                        NULLIF(COUNT(DISTINCT CASE WHEN hseir.incident_doc_type_id = $nearMiss AND hseir.status != '$exceptStatusNearMiss' THEN hseir.id END), 0) AS hseir_near_miss_count_vote, 
                        NULLIF(SUM(CASE WHEN hseir.status != '$exceptStatusLostDay' THEN hseir.lost_days END), 0) AS hseir_lost_day_count_vote
                    FROM hse_incident_reports hseir
                    WHERE 
                        hseir.work_area_id IN (SELECT id FROM temp_work_areas) 
                        AND SUBSTR(hseir.issue_datetime, 1, 4) = $year 
                    GROUP BY SUBSTR(hseir.issue_datetime, 1, 7)  
                ),
                t2 AS (
                    SELECT
                        SUBSTR(hsefa.injury_datetime, 1, 7) AS hse_month,
                        COUNT(CASE WHEN 1 = 1 THEN hsefa.id END) AS hsefa_count_vote
                    FROM hse_first_aids hsefa
                    WHERE 
                        hsefa.work_area_id IN (SELECT id FROM temp_work_areas)
                        AND SUBSTR(hsefa.injury_datetime, 1, 4) = $year  
                        AND hsefa.status = '$statusFirstAid'  
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
                                        AND hseicshts.status = '$statusICShts'
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
                                        AND hsew.status = '$statusWalkthrough'
                                    GROUP BY hse_month
                ),
                t5 AS (
                    SELECT
                                        SUBSTR(hseca.opened_at, 1, 7) AS hse_month,
                                        NULLIF(COUNT(DISTINCT CASE WHEN hseca.correctable_type = 'App\\\Models\\\Hse_walkthrough' 
                                                            OR hseca.correctable_type ='App\\\Models\\\Hse_insp_chklst_line' THEN hseca.id END),0) AS hseca_line_count 
                                    FROM hse_corrective_actions hseca
                                    WHERE 1 = 1
                                        AND hseca.work_area_id IN (SELECT id FROM temp_work_areas) 
                                        AND SUBSTR(hseca.opened_at, 1, 4) = $year
                                    GROUP BY hse_month
                ),
                t6 AS (
                                    SELECT
                                        SUBSTR(hrt.training_datetime, 1, 7) AS hse_month,
                                        NULLIF(COUNT( CASE WHEN hrtl.training_course_id = 2 AND hrt.status = '$statusHrTraining' THEN hrt.id END),0) AS hrt_line_count 
                                    FROM hr_trainings hrt, hr_training_lines hrtl
                                    WHERE 1 = 1
                                        AND hrt.training_location_id IN $strWorkplaceIds
                                        AND SUBSTR(hrt.training_datetime, 1, 4) = $year
                                        AND hrtl.hr_training_id = hrt.id
                                    GROUP BY hse_month
                ),
                t7 AS (
                    SELECT
                        SUBSTR(hseem.metric_month, 1, 7) AS hse_month,
                        MAX(hseem.total_discipline) AS discipline,
                        MAX(hseem.total_third_party_insp_audit) AS third_party_inspection_audit,
                        MAX(hseem.total_drill) AS drill,
                        MAX(hseem.total_meeting_toolbox) AS total_meeting_toolbox,
                        MAX(hseem.total_work_hours) AS hseem_total_work_hours
                    FROM hse_extra_metrics hseem
                    WHERE hseem.workplace_id IN $strWorkplaceIds
                        AND SUBSTR(hseem.metric_month, 1, 4) = $year
                        AND hseem.status = '$statusExtraMetric'
                    GROUP BY hse_month
                ),
                wp_ids AS (
                    SELECT GROUP_CONCAT(id) AS wp_id_list
                    FROM workplaces wp
                    WHERE wp.id IN $strWorkplaceIds
                ),

                -- CTE to generate all months for the year 2023
                all_months AS (
                    SELECT '$year-01' AS month UNION
                    SELECT '$year-02' AS month UNION
                    SELECT '$year-03' AS month UNION
                    SELECT '$year-04' AS month UNION
                    SELECT '$year-05' AS month UNION
                    SELECT '$year-06' AS month UNION
                    SELECT '$year-07' AS month UNION
                    SELECT '$year-08' AS month UNION
                    SELECT '$year-09' AS month UNION
                    SELECT '$year-10' AS month UNION
                    SELECT '$year-11' AS month UNION
                    SELECT '$year-12' AS month
                )

                -- Final query to include missing months and set counts to zero
                SELECT
                    missing_months.month AS missing_month,
                    SUBSTR(COALESCE(t1.hse_month, t2.hse_month, missing_months.month), 1, 4) AS year,
                    t1.*, t2.*,t3.*,t4.*, t5.*, t6.*,t7.*, wp_ids.wp_id_list
                FROM all_months as missing_months
                LEFT JOIN t1 ON missing_months.month = t1.hse_month
                LEFT JOIN t2 ON missing_months.month = t2.hse_month
                LEFT JOIN t3 ON missing_months.month = t3.hse_month
                LEFT JOIN t4 ON missing_months.month = t4.hse_month
                LEFT JOIN t5 ON missing_months.month = t5.hse_month
                LEFT JOIN t6 ON missing_months.month = t6.hse_month
                LEFT JOIN t7 ON missing_months.month = t7.hse_month
                CROSS JOIN wp_ids
                    
        ";
        return $sql;
    }

    protected function getTableColumns($dataSource, $modeParams)
    {

        $stringIcon = "class='text-base fa-duotone fa-circle-question hover:bg-blue-400 rounded'></i>";
        $notes = [
            'hseir_ltc_count_vote' => "<br/><i title='Lost Time Incident:\nLost time incidents are the result of a work-related injury or illness, where an employer or health care professional keeps or recommends keeping an employee from doing their job.\nUpdated Aug 2022:\nLost time days are counted from the day after the incident occurred.'" . $stringIcon,
            'hseir_rwc_count_vote' => "<br/><i title='Restricted Work Case:\nRestricted work activity occurs when, as the result of a work-related injury or illness, and employer or health care professional recommends keeping an employee from doing the routine functions of their job or from working the full workday as scheduled before the injury or illness.  If a single injury or illness involved both days away from work and restricted work, count the total for each category.\nUpdated Aug 2022:\nRestricted days counted from the day after the incident occurred.'" . $stringIcon,
            'hseir_mtc_count_vote' => "<br/><i title='Medical Treatment Case:\nMedical treatment includes managing and caring for a patient for the purpose of combating of disease or disorder. Any case that that falls beyond the scope of First Aid Case is to be considered as medical treatment. The administering of prescription medicine, sutures, broken bones, etc.'" . $stringIcon,
            'hsefa_count_vote' => "<br/><i title='First Aid Case:\nFirst aid is defined as routine treatment such as non-prescription medicine, tetanus shots, wound covering (bandages, gauze pads, Steri-strips, etc.), flushing or soaking wounds on the skin surface, use of non-rigid support (elastic bandages, wraps, etc.), eye patches, simple irrigation or cotton swabs to remove foreign bodies not embedded in or adhered to the eye, irrigation, tweezers or cotton swab to remove splinters or foreign material from areas other than the eye; drilling a fingernail or toenail to relieve pressure or draining fluids from blisters; hot or cold therapy.'" . $stringIcon,
            'hseir_near_miss_count_vote' => "<br/><i title='Near Miss:\nA near miss case is where energy has been released but no consequences have been realized, i.e. a hammer was dropped but it did not injure anyone nor did any damage when it hit the surface.'" . $stringIcon,
            'trir' => "<br/><i title='Total Recordable Incident Rate:\nTotal recordable incident (LTI,RWC,MTC,OI) rate is the total number of injuries and illnesses times 200,000 divided by number of hours worked by all employees.'" . $stringIcon,
            'hseir_incident_count_vote' => "<br/><i title='Oil Spill:\nWater Soluble Chemicals with high toxicity, such as water soluble or water dispersant corrosion inhibitors, biocides, reactive substances such as oxygen scavengers, methanol, concentrated acids and bases, sodium hypochlorite which are lost into the environment. By this we mean lost to sea, air or ground. A spill of oil in a workshop which is correctly contained / cleaned up and disposed correctly is NOT a spill to the environment.'" . $stringIcon,
            'total_meeting_toolbox' => "<br/><i title='HSE Meeting :\nToolbox meeting,committee meeting,pre-start meeting,other meeting related to HSE.'" . $stringIcon,
            'hrt_line_count' => "<br/><i title='HSE Training:\nHSE induction,HSE on jobs training,Third party training.'" . $stringIcon,
            'third_party_inspection_audit' => "<br/><i title='Third party inspections:\nGovernment, ISO,Smecta,other inspections of third party or client related to HSE.'" . $stringIcon,
            'hseca_line_count' => "<br/><i title='Safety Observations Frequency Rating:\nIf we are to eliminate injuries, damage or near miss incidents, we need to focus on at-risk acts and unsafe conditions, which have not yet caused loss or harm but have the potential to. Thus we need a systematic approach to observing, correcting and recording such at-risk behaviour or unsafe situations.\nThis is generally called safety observation (or hazard observation). The expected result is that by increasing safety observation, there would be a reduction in injuries, damage or near misses – the undesired events Number of safety observations x 200,000 / Total man-hours Safety Observation Report identifying at-risk behaviour, or an unsafe condition to prevent loss or harm e.g. ACT / ROC /STOP card or similar.'" . $stringIcon,
        ];


        $dataColumn = [
            [
                "title" => "Month",
                "dataIndex" => "hse_month",
                "align" => "center",
                "width" => 110,
            ],
            [
                "title" => "Work Hours",
                "dataIndex" => "work_hours",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "LTI {$notes['hseir_ltc_count_vote']}",
                "dataIndex" => "hseir_ltc_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "RWC {$notes['hseir_rwc_count_vote']}",
                "dataIndex" => "hseir_rwc_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "MTC {$notes['hseir_mtc_count_vote']}",
                "dataIndex" => "hseir_mtc_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Incident (Property damage, Oil spills) {$notes['hseir_incident_count_vote']}",
                "dataIndex" => "hseir_incident_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Near Miss {$notes['hseir_near_miss_count_vote']}",
                "dataIndex" => "hseir_near_miss_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "FAC & Medical Assistant  {$notes['hsefa_count_vote']}",
                "dataIndex" => "hsefa_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Lost Days",
                "dataIndex" => "hseir_lost_day_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "HSE Inspection",
                "dataIndex" => "hseicshts_tmpl_sht_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "HSE Walkthrough",
                "dataIndex" => "hsew_count_vote",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "HSE Observations {$notes['hseca_line_count']}",
                "dataIndex" => "hseca_line_count",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "HSE Training & Induction (Pax) {$notes['hrt_line_count']}",
                "dataIndex" => "hrt_line_count",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "HSE Meeting Toolbox & Committee  {$notes['total_meeting_toolbox']}",
                "dataIndex" => "total_meeting_toolbox",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Disciplines",
                "dataIndex" => "discipline",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Third party Inspections & Audit {$notes['third_party_inspection_audit']}",
                "dataIndex" => "third_party_inspection_audit",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "Drills",
                "dataIndex" => "drill",
                "align" => "right",
                "width" => 60,
            ],
            [
                "title" => "TRIR {$notes['trir']}",
                "dataIndex" => "trir",
                "align" => "right",
                "width" => 60,
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
        // dd($dataSource);
        if (is_object($dataSource)) $dataSource = array_map(fn ($item) => (array)$item, $dataSource->toArray());
        if (empty($dataSource)) return collect([]);

        $dbWorkplaceIds = DB::table('workplaces')->pluck('id')->toArray();
        $workplaceIds = isset($modeParams['many_workplace_id']) &&  $modeParams['many_workplace_id'][0] ? $modeParams['many_workplace_id'] : $dbWorkplaceIds;
        $workPlacesHoursOfYear  = [];
        foreach ($workplaceIds as $workplaceId) {
            $wp = Workplace::find($workplaceId);
            $year = isset($modeParams['year']) ? $modeParams['year'] : date('Y');

            foreach ([$year] as $y) {
                $workPlacesHoursOfYear[$y][] = $wp->getTotalWorkingHoursOfYear($y);
            }
        }

        $workHoursOfYear = Report::sumAndMergeNestedKeys($workPlacesHoursOfYear);
        $data = [];
        $hseMonths = [];
        foreach ($dataSource as $value) {
            $hseMonth = is_null($value['missing_month']) ? $value['hse_month'] : $value['missing_month'];
            if ($hseMonth) {
                $num = $value['hseem_total_work_hours'];
                [$year, $month] = explode('-', $hseMonth);
                if (!isset($workHoursOfYear[$year])) {
                    // Set year values to null
                    $value['work_hours'] = is_null($num) ? null : $num;
                    $value['trir'] = null;
                    $value['year'] = $year;
                    $value['hse_month'] = $hseMonth;
                    if (!in_array($hseMonth, $hseMonths)) $data[] = $value;
                    $hseMonths[] = $hseMonth;
                    continue;
                }
                $workHours = $workHoursOfYear[$year];
                if (!isset($workHours[$hseMonth])) {
                    // Set month values to null
                    $value['work_hours'] =  is_null($num) ? null : $num;
                    $value['trir'] = null;
                    $value['year'] = $year;
                    $value['hse_month'] = $hseMonth;
                    if (!in_array($hseMonth, $hseMonths)) $data[] = $value;
                    $hseMonths[] = $hseMonth;
                    continue;
                }
                $value['work_hours'] = is_null($num) ? $workHours[$hseMonth] : $num;
                $value['hse_month'] = $hseMonth;
                // Calculate the total recordable incident rate (TRIR)
                $totalRecIncidentRate = (($value['hseir_ltc_count_vote']
                    + $value['hseir_rwc_count_vote']
                    + $value['hseir_mtc_count_vote']
                    + $value['hseir_incident_count_vote']
                    + $value['hseir_near_miss_count_vote']) * 200000) / $value['work_hours'];
                $value['trir'] = ($num = round($totalRecIncidentRate, 2)) ? $num : null;
                if (!in_array($hseMonth, $hseMonths)){
                    $value['work_hours'] = round($value['work_hours'], 2);
                    $data[] = $value;
                } 
                $hseMonths[] = $hseMonth;
            }
        }
        $dataSource = $data;

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
        if($dataFooter['work_hours']) {
            $dataFooter['trir'] = round((($dataFooter['hseir_ltc_count_vote']
                + $dataFooter['hseir_rwc_count_vote']
                + $dataFooter['hseir_mtc_count_vote']
                + $dataFooter['hseir_incident_count_vote']
                + $dataFooter['hseir_near_miss_count_vote']) * 200000) / $dataFooter['work_hours'], 2);
        }
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
        // dump($dataSource);
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
