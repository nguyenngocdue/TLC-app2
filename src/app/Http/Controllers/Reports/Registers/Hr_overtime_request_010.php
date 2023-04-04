<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Workplace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Hr_overtime_request_010 extends Report_ParentController
{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    protected $groupBy = 'name_render';
    protected $groupByLength = 1;

    public function getSqlStr($modeParams)
    {
        $sql = " SELECT tb3.*
        FROM (SELECT tb2.*,LAG(remaining_allowed_ot_hours_year, 1, 200) OVER (PARTITION BY years_month, user_id ORDER BY year_months) AS maximum_allowed_ot_hours_year
        FROM(SELECT 
        GROUP_CONCAT(distinct workplace SEPARATOR ', ') AS ot_workplace,
            user_category_name,
            user_category_desc,
            MAX(name_render) AS name_render,
            MAX(user_id) AS user_id,
            MAX(user_workplace) AS user_workplace,
            employee_id,
            year_months,
            (40) AS maximum_allowed_ot_hours,
            SUM(total_overtime_hours) AS total_overtime_hours,
            (40 - SUM(total_overtime_hours)) AS remaining_allowed_ot_hours,
            years_month,
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
                        us.name AS name_render,
                        uscate.name AS user_category_name,
                        uscate.description AS user_category_desc,
                        otline.employeeid AS employee_id,
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
                    AND uscate.id = us.category
                    AND  otline.status LIKE 'approved'";

        if (isset($modeParams['user_id'])) $sql .= "\n AND us.id = '{{user_id}}'";
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
        ORDER BY name_render, employee_id, year_months DESC ) AS tb3
        WHERE 1 = 1";
        // dd($modeParams);
        if (isset($modeParams['months'])) $sql .= "\n AND year_months = '{{months}}'";
        return $sql;
    }


    // Table
    public function getTableColumns($dataSource, $modeParams)
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
                "title" => "Full Name",
                "dataIndex" => "name_render",
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
                "title" => "Total Overtime Hours (Month)",
                "dataIndex" => "total_overtime_hours",
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
                'dataIndex' => 'ot_workplace_id',
                'allowClear' => true,
            ],
            [
                'dataIndex' => 'months',
                'allowClear' => true,
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }

    // Mode
    protected function modeColumns()
    {
        return [
            'title' => 'Mode',
            'dataIndex' => 'mode_option',
            'allowClear' => true
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
    private function getOTUsers()
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

        $sqlUsers = $this->getOTUsers();
        $us = array_column($sqlUsers, 'name',  'user_id');
        $users = ['user_id' => $us];

        return array_merge($workplaces, $months, $users);
    }

    private function wrapValueInObjectWithCellColor($percent, $value)
    {
        // dump($value);
        switch (true) {
            case $percent < 0:
                return (object)[
                    'cell_class' => 'bg-red-600 ',
                    'value' => $value,
                    'cell_title' => $percent . '%',
                ];
            case $percent > 0 && $percent < 25:
                return (object)[
                    'cell_class' => 'bg-pink-400',
                    'value' => $value,
                    'cell_title' => $percent . '%',
                ];
            case $percent >= 25 && $percent < 50:
                return (object)[
                    'cell_class' => 'bg-orange-300',
                    'value' => $value,
                    'cell_title' => $percent . '%',
                ];
            case $percent >= 50 && $percent < 75:
                return (object)[
                    'cell_class' => 'bg-yellow-300',
                    'value' => $value,
                    'cell_title' => $percent . '%',
                ];
            case $percent >= 75:
                return (object)[
                    'cell_class' => 'bg-green-300',
                    'value' => $value,
                    'cell_title' => $percent . '%',
                ];
        }
    }

    protected function getColorLegends()
    {
        return [
            'remaining_allowed_OT_hours_legend' => [
                'bg-red-400' => '< 0%',
                'bg-pink-400' => '0% to < 25%',
                'bg-orange-300' => '25% to < 50%',
                'bg-yellow-400' => '50% to < 75%',
                'bg-green-400' => '>= 75%',
            ]
        ];
    }

    protected function enrichDataSource($dataSource, $modeParams)
    {
        // dd($dataSource);
        $type = Str::singular($this->getType());
        foreach ($dataSource as $key => $value) {
            // display name/description for total_overtime_hours
            $teamName = $value->user_category_name;
            $teamDesc = $value->user_category_desc;
            $htmlTeam = "<span title='$teamDesc'>$teamName</span>";
            $htmlEmployeeId = "<span title='User ID: $value->user_id'>$value->employee_id</span>";

            $dataSource[$key]->user_category_name = $htmlTeam;
            $dataSource[$key]->employee_id = $htmlEmployeeId;

            // display colors for total_overtime_hours
            $remainingAllowedOTHoursMonth = ($value->remaining_allowed_ot_hours * 1);
            $remainingAllowedOTHoursYear = $value->remaining_allowed_ot_hours_year * 1;

            $percentOTMonth = $remainingAllowedOTHoursMonth / 40 * 100;
            $percentOTYear = $remainingAllowedOTHoursYear / 200 * 100;

            // dump($remainingAllowedOTHoursMonth);
            $param = '?user_id=' . $value->user_id . '&' . 'months=' . $value->year_months;
            $reAllowedOTHoursMonth = $this->wrapValueInObjectWithCellColor($percentOTMonth, $remainingAllowedOTHoursMonth);
            $reAllowedOTHoursYear = $this->wrapValueInObjectWithCellColor($percentOTYear, $remainingAllowedOTHoursYear);

            $dataSource[$key]->remaining_allowed_ot_hours = $reAllowedOTHoursMonth ?? $value->remaining_allowed_ot_hours;
            $dataSource[$key]->remaining_allowed_ot_hours_year = $reAllowedOTHoursYear;

            $hrefForward = route('dashboard') . "/reports/register-" . $type . "/020" . $param;
            $dataSource[$key]->total_overtime_hours = (object)[
                'value' => $value->total_overtime_hours,
                'cell_href' => $hrefForward
            ];
        }
        return $dataSource;
    }
}
