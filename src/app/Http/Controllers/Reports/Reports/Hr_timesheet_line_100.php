<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDataModesReport;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Models\User;
use App\Utils\Support\Report;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Str;

class Hr_timesheet_line_100 extends Report_ParentReportController

{
    use TraitDynamicColumnsTableReport;
    use TraitDataModesReport;
    protected $maxH = 50;
    protected $mode = '100';
    #protected $rotate45Width = 300;
    protected $libPivotFilters;

    public function getSqlStr($modeParams)
    {
        $pickerDate = $modeParams['picker_date'] ?? self::defaultPickerDate();
        [$startDate, $endDate] = Report::explodePickerDate($pickerDate);
        $startDate = Report::formatDateString($startDate, 'Y-m-d');
        $endDate = Report::formatDateString($endDate, 'Y-m-d');
        $sql = "SELECT
                    tsl.project_id AS project_id,
                    DATE(tsl.start_time) AS time_sheet_start_time,
                    DATE(tsl.start_time) AS time_sheet_start_time_wfh,
                    DATE(tsl.start_time) AS time_sheet_start_time_otr,
                    SUM(tsl.duration_in_min) AS time_sheet_durations,
                    SUM(0) AS time_sheet_durations_wfh,
                    SUM(0) AS time_sheet_durations_otr,
                    SUM(tsl.ts_hour) AS time_sheet_hours,

                    tsl.sub_project_id AS sub_project_id,
                    tsl.user_id AS user_id,
                    tsl.discipline_id AS discipline_id,
                    tsl.lod_id AS lod_id,
                    tsl.task_id AS pj_task_id,
                    us.department AS department_id,
                    us.user_type AS type_id,
                    us.category AS category_id,
                    us.employeeid AS staff_id,
                    us.workplace AS workplace_id

                FROM
                    hr_timesheet_lines tsl
                INNER JOIN \n";

        if (isset($modeParams['many_user_id']) || isset($modeParams['user_id'])) {
            $ids = implode(',', $modeParams['many_user_id'] ?? $modeParams['user_id']);
            $sql .= "users us ON tsl.user_id IN ($ids) AND us.id = tsl.user_id";
        } else {
            $sql .= "users us ON tsl.user_id = us.id";
        }
        $sql .= "\n WHERE 1 = 1 
                    AND DATE(tsl.start_time) BETWEEN '$startDate' AND '$endDate'
                    #AND tsl.sub_project_id IN (82, 21)
                GROUP BY
                    time_sheet_start_time,
                    pj_task_id,
                    project_id,
                    sub_project_id,
                    user_id,
                    discipline_id,
                    lod_id,
                    department_id,
                    type_id,
                    category_id,
                    staff_id,
                    workplace_id
                ORDER BY
                    user_id";
        return $sql;
    }
    protected function getDefaultValueModeParams($modeParams, $request)
    {
        $pickerDate = 'picker_date';
        $isNullModeParams = Report::isNullModeParams($modeParams);
        if ($isNullModeParams) {
            $modeParams[$pickerDate] = self::defaultPickerDate();
        }
        return $modeParams;
    }

    private function defaultPickerDate()
    {
        $currentDate = new DateTime();
        $targetDate = clone $currentDate;
        $targetDate->modify('-6 months');
        $targetDate->modify('-1 day');
        return date($targetDate->format('d/m/Y')) . '-' . date($currentDate->format('d/m/Y'));
    }

    private function isSaturdayOrSunday($dateString)
    {
        $date = DateTime::createFromFormat("d/m/Y", $dateString);
        // dump($dateString);
        if ($date === false) return '';
        $dayOfWeek = $date->format("N"); // Retrieve the day of the week as a string
        if ($dayOfWeek >= 6) return true;
        return false;
    }

    private function getDatesBetween($startDate, $endDate)
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

    protected function getTableColumns($dataSource, $modeParams)
    {
        $strDates = $this->getStringDates($modeParams);
        $dataColumn1 = [
            [
                "title" => "Sub Project",
                "dataIndex" => "sub_project_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                'title' => "LOD",
                "dataIndex" => "lod_name",
                "align" => 'left',
                "width" => 130,
            ],
            [
                'title' => "LOD",
                "dataIndex" => "lod_id",
                "align" => 'left',
                "width" => 130,
            ],
            [
                "title" => "Department",
                "dataIndex" => "department_name",
                "align" => 'left',
                "width" => 180,
            ],
            [
                "dataIndex" => "department_id",
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
                "width" => 600,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "dataIndex" => "workplace_id",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Category",
                "dataIndex" => "category_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "dataIndex" => "category_id",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "title" => "Type",
                "dataIndex" => "type_name",
                "align" => 'left',
                "width" => 80,
            ],
            [
                "dataIndex" => "type_id",
                "align" => 'left',
                "width" => 120,
            ],
            [
                "title" => "Discipline",
                "dataIndex" => "discipline_name",
                "align" => 'left',
                "width" => 220,
            ],
            [
                "dataIndex" => "discipline_id",
                "align" => 'left',
                "width" => 220,
            ],
            [
                "title" => "Task",
                "dataIndex" => "pj_task_name",
                "align" => 'left',
                "width" => 280,
            ],
            [
                "dataIndex" => "pj_task_id",
                "align" => 'left',
                "width" => 280,
            ],
            [
                "title" => "Date",
                "dataIndex" => "time_sheet_start_time",
                "align" => 'left',
                "width" => 300,
            ],
            [
                "title" => "Duration (min)",
                "dataIndex" => "time_sheet_durations",
                "align" => 'right',
                "width" => 50,
            ]
        ];
        return $dataColumn1;
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
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }

    private function addInfoHeadUsers($infoHeadUsers, $dataWaitingFor)
    {
        $users = $dataWaitingFor['users'];
        $infoHeadUsers['user_name'] = $users[$infoHeadUsers['user_id']]['name'];
        $infoHeadUsers['staff_id'] =  $users[$infoHeadUsers['user_id']]['employeeid'];
        $infoHeadUsers['workplace_id'] = $users[$infoHeadUsers['user_id']]['workplace'];
        $infoHeadUsers['type_id'] = $users[$infoHeadUsers['user_id']]['user_type'];
        $infoHeadUsers['department_id'] = $users[$infoHeadUsers['user_id']]['department'];
        $infoHeadUsers['category_id'] = $users[$infoHeadUsers['user_id']]['category'];

        $infoHeadUsers['project_name'] = $dataWaitingFor['projects'][$infoHeadUsers['project_id']];
        $infoHeadUsers['sub_project_name'] = $dataWaitingFor['sub_projects'][$infoHeadUsers['sub_project_id']];
        $infoHeadUsers['lod_name'] = $dataWaitingFor['terms'][$infoHeadUsers['lod_id']];
        $infoHeadUsers['discipline_name'] = $dataWaitingFor['user_disciplines'][$infoHeadUsers['discipline_id']];
        $infoHeadUsers['pj_task_name'] = $dataWaitingFor['pj_tasks'][$infoHeadUsers['pj_task_id']];

        $infoHeadUsers['type_name'] = $dataWaitingFor['user_types'][$infoHeadUsers['type_id']];
        $infoHeadUsers['workplace_name'] = $dataWaitingFor['workplaces'][$infoHeadUsers['workplace_id']];
        $infoHeadUsers['category_name'] =  $dataWaitingFor['user_categories'][$infoHeadUsers['category_id']];
        $infoHeadUsers['department_name'] = $dataWaitingFor['departments'][$infoHeadUsers['department_id']];

        // dd($infoHeadUsers, $dataWaitingFor);
        return $infoHeadUsers;
    }
    private function dataWaitingForLooking()
    {
        $arrayModelNames = ['Project', 'Sub_project', 'Term', 'User_Type', 'User_discipline', 'Workplace', 'Pj_task', 'User_category', 'Department'];
        $dataModels = array_map(
            function ($modelName) {
                $key = Str::plural(strtolower($modelName));
                $value = array_column(Str::modelPathFrom($modelName)::select('id', 'name')->get()->toArray(), 'name', 'id');
                return [$key => $value];
            },
            $arrayModelNames
        );
        $dataModels = array_merge(...$dataModels);
        $users = User::select('id', 'name', 'employeeid', 'user_type', 'workplace', 'category', 'department')->get()->toArray();;
        $users =  array_map(fn ($item)  => $item[0], Report::groupArrayByKey($users, 'id'));
        return ['users' => $users] + $dataModels;
    }


    protected function transformDataSource($dataSource, $modeParams)
    {
        // dd($this->a);
        $data = array_slice($dataSource->toArray(), 0, 100);
        $dataWaitingFor = $this->dataWaitingForLooking();
        $dataSource = array_map(fn ($item) => (array)$item, $data);
        array_walk($dataSource, function ($value, $key) use (&$dataSource, $dataWaitingFor) {
            $datesHaveValue = Report::retrieveDataByIndex($value, 'pj_task_id');
            $infoHeadUsers = Report::retrieveDataByIndex($value, 'pj_task_id', true);
            $dataHeadUsers = $this->addInfoHeadUsers($infoHeadUsers, $dataWaitingFor);
            $dataSource[$key] =  $dataHeadUsers + $datesHaveValue;
        });
        // $this->hr_timesheet_line_020($dataSource);
        return collect($dataSource);
    }

    private function hr_timesheet_line_020($dataSource)
    {
        $libPivotFilters = $this->libPivotFilters;
        $rowFields = $libPivotFilters['row_fields'];

        $data = [];

        foreach ($rowFields as $keyCol => $nameCol) {
            $dataGroup1 = Report::groupArrayByKey($dataSource, $nameCol . '_name');
            ++$keyCol;
            $data = [];
            if ($keyCol < count($rowFields)) {
                foreach ($dataGroup1 as $k1 => $g1) {
                    $dataGroup1 = Report::groupArrayByKey($g1, $rowFields[$keyCol] . '_name');
                    ++$keyCol;
                    foreach ($dataGroup1 as $k2 => $g2) {
                        $dataGroup2 = Report::groupArrayByKey($g2, $rowFields[$keyCol] . '_name');
                        ++$keyCol;
                        if ($keyCol < count($rowFields)) {
                            foreach ($dataGroup2 as $k3 => $g3) {
                                $dataGroup3 = Report::groupArrayByKey($g3, $rowFields[$keyCol] . '_name');
                                $data[$k1][$k2][$k3] = $dataGroup3;
                            }
                        }
                    }
                }
                ++$keyCol;
            }
        }
        dd($libPivotFilters);
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        foreach ($dataSource as $key => $values) {
            $dateStrings = Report::retrieveDataByIndex($values, 'department_name');
            foreach ($dateStrings as $dateString => $value) {
                $_dateString = str_replace('_', '/', $dateString);
                $isSaturdayOrSunday = $this->isSaturdayOrSunday($_dateString);
                if ($isSaturdayOrSunday) {
                    $dateStrings[$dateString] = (object)[
                        'value' => $value,
                        'cell_class' => 'bg-gray-100',
                    ];
                } else {
                    // dd($values);
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
                'value' => $values['type_name'],
                'cell_title' => 'ID: ' . $values['type_id'],
            ];

            $dataSource[$key] = array_merge($values, $dateStrings);
        }
        // dd($dataSource);
        return $dataSource;
    }
}
