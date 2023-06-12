<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Utils\Support\Report;
use DateInterval;
use DatePeriod;
use DateTime;

class Hr_timesheet_line_010 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;

    protected  $picker_date = "01/01/2023 - 01/02/2023";
    protected $maxH = 50;
    protected $rotate45Width = 300;
    protected $tableTrueWidth = true;

    public function getSqlStr($modeParams)
    {
        $pickerDate = $modeParams['picker_date'];
        [$startDate, $endDate] = Report::explodePickerDate($pickerDate);
        $startDate = Report::formatStringDate($startDate, 'Y-m-d');
        $endDate = Report::formatStringDate($endDate, 'Y-m-d');
        $sql = "SELECT
        -- tsl.id AS time_sheet_id,
        tsl.project_id AS project_id,
        DATE(tsl.start_time) AS time_sheet_start_time,
        SUM(tsl.ts_hour) AS time_sheet_hours,
        SUM(tsl.duration_in_min) AS time_sheet_durations,
        tsl.sub_project_id AS sub_project_id,
        sp.name AS sub_project_name,
        term.name AS time_sheet_lod_name,
        dep.name AS user_department_name,
        us.employeeid AS staff_id,
        tsl.user_id AS user_id,
        us.name AS user_name,
        us.workplace AS workplace_id,
        wp.name AS workplace_name,
        #ucate.id AS user_category_id,
        ucate.name AS user_category_name,
        ust.id AS user_type_id,
        ust.name AS user_type_name,
        #us.id AS user_discipline_id,
        udl.name AS user_discipline_name,
        pr.name AS project_name,
        tsl.task_id AS pj_task_id,
        pjtk.name AS pj_task_name
        FROM 
            hr_timesheet_lines tsl,
            projects pr,
            sub_projects sp,
            users  us,
            terms term,
            departments dep,
            workplaces wp, 
            user_categories ucate, 
            user_disciplines udl,
            user_types ust,
            pj_tasks pjtk
            WHERE 1 = 1
            AND us.id = tsl.user_id
            AND sp.project_id = pr.id
            AND wp.id = us.workplace
            AND us.category = ucate.id 
            AND us.user_type = ust.id 
            AND us.discipline = udl.id
            AND pjtk.id = tsl.task_id
            AND pr.id = tsl.project_id
            AND term.id = tsl.lod_id
            AND dep.id = us.department
            AND DATE(tsl.start_time) BETWEEN '$startDate' AND '$endDate'";
        if (isset($modeParams['user_id'])) $sql .= "\n AND us.id = '{{user_id}}'";
        if (isset($modeParams['sub_project_id'])) $sql .= "\n AND sp.id = '{{sub_project_id}}'";
        if (isset($modeParams['workplace_id'])) $sql .= "\n AND wp.id = '{{workplace_id}}'";
        if (isset($modeParams['department_id'])) $sql .= "\n AND dep.id = '{{department_id}}'";

        $sql .= "\n GROUP BY time_sheet_start_time, pj_task_name, project_name,project_id,sub_project_id,sub_project_name, user_id, user_name, pj_task_id, time_sheet_lod_name
            ORDER BY user_name;";
        return $sql;
    }

    function getDatesBetween($startDate, $endDate)
    {
        $dateList = array();
        // Convert start and end dates to DateTime objects
        $start = DateTime::createFromFormat('d/m/Y', $startDate);
        $end = DateTime::createFromFormat('d/m/Y', $endDate);
        // Include the end date in the range
        $end = $end->modify('+1 day');
        // Iterate over the range of dates
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        foreach ($dateRange as $date) {
            $dateList[] = $date->format('d/m/Y');
        }
        return $dateList;
    }

    private function getStringDates($modeParams)
    {
        $pickerDate = $modeParams['picker_date'];
        [$startDate, $endDate] = Report::explodePickerDate($pickerDate);
        $dates = self::getDatesBetween($startDate, $endDate);
        $strDates = array_map(fn ($item) => str_replace('/', '_', $item), $dates);
        return $strDates;
    }

    public function getTableColumns($dataSource, $modeParams)
    {
        $strDates = $this->getStringDates($modeParams);
        $dataColumn1 = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => 'left',
                "width" => 130,
            ],
            [
                'title' => "LOD",
                "dataIndex" => "time_sheet_lod_name",
                "align" => 'left',
                "width" => 130,
            ],
            [
                "title" => "Department",
                "dataIndex" => "user_department_name",
                "align" => 'left',
                "width" => 180,
            ],
            [
                "title" => "Staff ID",
                "dataIndex" => "staff_id",
                "align" => 'left',
                "width" => 120,
            ],
            [
                "title" => "User",
                "dataIndex" => "user_name",
                "align" => 'left',
                "width" => 200,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Category",
                "dataIndex" => "user_category_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Type",
                "dataIndex" => "user_type_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Discipline",
                "dataIndex" => "user_discipline_name",
                "align" => 'left',
                "width" => 220,
            ],
            // [
            //     "dataIndex" => "time_sheet_start_time",
            //     "align" => "center",
            // ],
            [
                "title" => "Task",
                "dataIndex" => "pj_task_name",
                "align" => 'left',
                "width" => 280,
            ],
            [
                "title" => "Total",
                "dataIndex" => "total_time",
                "align" => 'right',
                "width" => 50,
            ]
        ];
        $dataColumn2 = array_map(fn ($item) => [
            "title" => str_replace('_', '/', $item),
            "dataIndex" => $item,
            "align" => 'center',
            "width" => 40,
        ], $strDates);

        $data = array_merge($dataColumn1, $dataColumn2);
        return $data;
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
            ],
            [
                'title' => 'Sub Project',
                'dataIndex' => 'sub_project_id',
                'allowClear' => true,
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'workplace_id',
                'allowClear' => true,
            ],
            [
                'title' => 'Department',
                'dataIndex' => 'department_id',
                'allowClear' => true,
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        $data = array_slice($dataSource->toArray(), 0, 1000000);
        // dd($data);
        $groupUsers = Report::groupArrayByKey($data, 'user_id');
        // dd($groupUsers);
        $groupTaskNames = array_map(fn ($item) => Report::groupArrayByKey($item, 'pj_task_name'), $groupUsers);

        $valueUser = array_values($groupTaskNames);
        $datesData =  array_map(function ($items) {
            $dates = [];
            foreach (array_values($items) as $value) {
                $groupProjectName = Report::groupArrayByKey($value, 'project_name');
                $timeTransfer = array_map(fn ($array) => Report::transferValueOfKey($array, 'time_sheet_start_time', 'time_sheet_hours'), $groupProjectName);
                $timeTransfer = Report::mergeArrayValues($timeTransfer);
                $dates += $timeTransfer;
            }
            return $dates;
        }, $valueUser);
        $dataSource =  array_merge(...$datesData);
        $strDates = $this->getStringDates($modeParams);
        array_walk($dataSource, function ($value, $key) use ($strDates, &$dataSource) {
            $datesHaveValue = Report::retrieveDataByKeyIndex($value, 'pj_task_name');
            $totalTime = array_sum($datesHaveValue);
            $diffArray = array_diff(array_values($strDates), array_keys($datesHaveValue));
            $diffArray = ['total_time' => $totalTime] + array_fill_keys(array_values($diffArray), '');
            $dataSource[$key] = $value + $diffArray;
        });
        // dd($dataSource);
        return collect($dataSource);
    }

    private function isSaturdayOrSunday($dateString)
    {
        $date = DateTime::createFromFormat("d/m/Y", $dateString);
        if ($date === false) return dd($dateString);
        $dayOfWeek = $date->format("N"); // Retrieve the day of the week as a string
        if ($dayOfWeek >= 6) return true;
        return false;
    }



    protected function changeValueData($dataSource, $modeParams)
    {
        foreach ($dataSource as $key => $values) {
            $dateStrings = Report::retrieveDataByKeyIndex($values, 'total_time');
            foreach ($dateStrings as $dateString => $value) {
                $_dateString = str_replace('_', '/', $dateString);
                $isSaturdayOrSunday = $this->isSaturdayOrSunday($_dateString);
                if ($isSaturdayOrSunday) {
                    $dateStrings[$dateString] = (object)[
                        'value' => $value,
                        'cell_class' => 'bg-gray-100',
                    ];
                }
            }
            // dd($dateStrings);
            $values['project_name'] = (object) [
                'value' => $values['project_name'],
                'cell_title' => 'ID: ' . $values['project_id'],
            ];
            $values['sub_project_name'] = (object) [
                'value' => $values['sub_project_name'],
                'cell_title' => 'ID: ' . $values['sub_project_id'],
            ];
            $values['user_name'] = (object) [
                'value' => $values['user_name'],
                'cell_title' => 'ID: ' . $values['user_id'],
            ];
            $values['workplace_name'] = (object) [
                'value' => $values['workplace_name'],
                'cell_title' => 'ID: ' . $values['workplace_id'],
            ];
            $values['user_type_name'] = (object) [
                'value' => $values['user_type_name'],
                'cell_title' => 'ID: ' . $values['user_type_id'],
            ];

            $dataSource[$key] = array_merge($values, $dateStrings);
        }
        return $dataSource;
    }



    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $pickerDate = 'picker_date';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$pickerDate] = $this->picker_date;
        }
        return $modeParams;
    }
}
