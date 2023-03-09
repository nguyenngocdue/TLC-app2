<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Workplace;
use Illuminate\Support\Facades\DB;

class Hr_overtime_request extends Report_ParentController
{
    use TraitReport;
    protected $groupBy = 'first_name';
    public function getSqlStr($modeParams)
    {
        $sql = " SELECT tb2.*,LAG(remaining_allowed_ot_hours_year, 1, 200) OVER (PARTITION BY years_month ORDER BY year_months) AS maximum_allowed_ot_hours_year
        FROM(SELECT 
        GROUP_CONCAT(distinct workplace SEPARATOR ', ') AS ot_workplace,
            user_category_name,
            user_category_desc,
            MAX(first_name) AS first_name,
            MAX(last_name) AS last_name,
            MAX(user_id) AS user_id,
            MAX(user_workplace) AS user_workplace,
            employee_id,
            year_months,
            (40) AS maximum_allowed_ot_hours,
            SUM(total_overtime_hours) AS total_overtime_hours,
            (40 - SUM(total_overtime_hours)) AS remaining_allowed_ot_hours,
            years_month,
            (200) AS maximum_allowed_ot_hours_year,
            ROW_NUMBER() OVER (PARTITION BY employee_id, years_month ORDER BY year_months) AS step_plus,
            SUM(SUM(total_overtime_hours)) OVER (PARTITION BY employee_id, years_month ORDER BY year_months) AS cumulative_remaining_hours_year,
            (200 - SUM(SUM(total_overtime_hours)) OVER (PARTITION BY employee_id, years_month ORDER BY year_months)) AS remaining_allowed_ot_hours_year
            
        FROM (
            SELECT otTb.*, wpus.name AS user_workplace
            FROM (
                SELECT 
                    wp.name AS workplace,
                    rgt_ot.*
                FROM (
                    SELECT 
                        us.workplace AS user_workplace_id,
                        otr.workplace_id AS ot_workplace_id,
                        us.first_name AS first_name,
                        us.last_name AS last_name,
                        uscate.name AS user_category_name,
                        uscate.description AS user_category_desc,
                        otline.employeeid AS employee_id,
                        us.full_name AS member_name,
                        SUBSTR(otline.ot_date,1,7) AS year_months,
                        SUBSTR(otline.ot_date,1,4) AS years_month,
                        SUM(otline.total_time) AS total_overtime_hours,
                        us.id AS user_id
                    FROM 
                        users us, 
                        hr_overtime_request_lines otline, 
                        hr_overtime_requests otr, 
                        user_categories uscate
                    WHERE 1 = 1
                    AND otline.user_id = us.id
                    AND otline.hr_overtime_request_id = otr.id
                    AND uscate.id = us.category";

        if (isset($modeParams['user_id'])) $sql .= "\n AND us.id = '{{user_id}}'";
        if (isset($modeParams['months'])) $sql .= "\n AND SUBSTR(otline.ot_date,1,7) = '{{months}}'";
        $sql .= "\nGROUP BY user_id, employee_id, year_months, ot_workplace_id, years_month
                ) AS rgt_ot 
                JOIN workplaces wp ON wp.id = rgt_ot.ot_workplace_id";
        if (isset($modeParams['ot_workplace_id'])) $sql .= "\n AND wp.id = '{{ot_workplace_id}}'";
        $sql .= "\n) AS otTb, workplaces wpus
            WHERE 1 = 1
            AND otTb.user_workplace_id = wpus.id
            GROUP BY user_id, employee_id, year_months, ot_workplace_id, years_month
        ) tbg
        GROUP BY year_months, years_month, employee_id, user_category_name, user_category_desc
        ) tb2
        ORDER BY first_name, last_name, employee_id, year_months DESC";
        return $sql;
    }

    public function getTableColumns($dataSource)
    {
        // dump($dataSource);
        return [
            [
                'title' => 'OT Workplace',
                "dataIndex" => "ot_workplace",
                "align" => 'left'
            ],
            [
                "title" => "Team",
                "dataIndex" => "user_category_name",
                "align" => 'left'
            ],
            [
                "title" => "Employee ID",
                "dataIndex" => "employee_id",
                "align" => 'left'
            ],
            [
                "dataIndex" => "first_name",
                "align" => 'left'
            ],
            [
                "dataIndex" => "last_name",
                "align" => 'left'
            ],
            [
                "dataIndex" => "user_workplace",
                "align" => 'left'
            ],
            [
                "title" => "Months",
                "dataIndex" => "year_months",
                "align" => "right",
            ],
            [
                "title" => "Maximum Allowed OT Hours (Month)",
                "dataIndex" => "maximum_allowed_ot_hours",
                "align" => "right",
            ],
            [

                "title" => "Total Overtime Hours (Month)",
                "dataIndex" => "total_overtime_hours",
                "align" => "right",
            ],
            [
                "title" => "Remaining Allowed OT Hours (Month)",
                "dataIndex" => "remaining_allowed_ot_hours",
                "align" => "right",
            ],
            [
                "title" => "Years",
                "dataIndex" => "years_month",
                "align" => "right",
            ],

            [
                "title" => "Maximum Allowed OT Hours (Year)",
                "dataIndex" => "maximum_allowed_ot_hours_year",
                "align" => "right",
            ],
            [
                "title" => "Cumulative Total Hours (Year)",
                "dataIndex" => "cumulative_remaining_hours_year",
                "align" => "right",
            ],
            [
                "title" => "Remaining Allowed OT Hours (Year)",
                "dataIndex" => "remaining_allowed_ot_hours_year",
                "align" => "right",
            ],
        ];
    }

    protected function getParamColumns()
    {
        return [
            [
                'title' => 'OT Workplace',
                'dataIndex' => 'ot_workplace_id'
            ],
            [
                'dataIndex' => 'months'
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id'
            ]
        ];
    }

    private function getAllMonths()
    {
        $sql = "SELECT DISTINCT(SUBSTR(otline.ot_date, 1, 7)) AS year_months
                    FROM hr_overtime_request_lines otline
                    ORDER BY year_months DESC";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }
    private function getAllOtUser()
    {
        $sql = "SELECT DISTINCT(us.id) AS user_id, us.name
                FROM hr_overtime_request_lines otline, users us
                WHERE us.id = otline.user_id ORDER BY user_id";
        $sqlData = DB::select(DB::raw($sql));
        return $sqlData;
    }


    public function getDataForModeControl($dataSource)
    {
        $workplaces = ['ot_workplace_id' => Workplace::get()->pluck('name', 'id')->toArray()];

        $sqlMonths = $this->getAllMonths();
        $mon = array_column($sqlMonths, 'year_months');
        $months = ['months' => array_combine($mon, $mon)];

        $sqlUsers = $this->getAllOtUser();
        $us = array_column($sqlUsers, 'name',  'user_id');
        $users = ['user_id' => $us];

        return array_merge($workplaces, $months, $users);
    }
    private function wrapValueInObjectWithCellColor($value, $index)
    {
        $levelTime = [
            [40, 30, 20, 10, 0],
            [200, 150, 100, 50, 0],
        ];
        switch (true) {
            case $value > $levelTime[$index][0]:
                return (object)[
                    'cell_color' => 'bg-red-400',
                    'value' => $value,
                ];
            case $value > $levelTime[$index][1]:
                return (object)[
                    'cell_color' => 'bg-pink-400',
                    'value' => $value,
                ];
            case $value > $levelTime[$index][2]:
                return (object)[
                    'cell_color' => 'bg-yellow-400',
                    'value' => $value,
                ];
            case $levelTime[$index][3] >= 0:
                return (object)[
                    'cell_color' => 'bg-green-400',
                    'value' => $value,
                ];
        }
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {

        foreach ($dataSource as $key => $value) {

            // display name/description for total_overtime_hours
            $teamName = $value->user_category_name;
            $teamDesc = $value->user_category_desc;
            $htmlTeam = "<span title='$teamDesc'>$teamName</span>";
            $htmlEmployeeId = "<span title='User ID: $value->user_id'>$value->employee_id</span>";
            $dataSource[$key]->user_category_name = $htmlTeam;
            $dataSource[$key]->employee_id = $htmlEmployeeId;

            // display colors for total_overtime_hours
            $totalOvertimeHour = $value->total_overtime_hours * 1;
            $cumulativeRemainingHours = $value->cumulative_remaining_hours_year * 1;

            $strTotalOvertimeHour = $this->wrapValueInObjectWithCellColor($totalOvertimeHour, 0);
            $strCumulativeRemainingHours = $this->wrapValueInObjectWithCellColor($cumulativeRemainingHours, 1);

            $dataSource[$key]->total_overtime_hours = $strTotalOvertimeHour;
            $dataSource[$key]->cumulative_remaining_hours_year = $strCumulativeRemainingHours;
        }
        // dd($dataSource);
        return $dataSource;
    }
}
