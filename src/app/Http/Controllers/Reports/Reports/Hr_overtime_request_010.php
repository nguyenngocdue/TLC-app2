<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReportController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitLegendReport;
use App\Http\Controllers\Reports\TraitModifyDataToExcelReport;
use App\Http\Controllers\Reports\TraitUserCompanyTree;
use App\Utils\Support\CurrentUser;

class Hr_overtime_request_010 extends Report_ParentReportController
{
    use TraitDynamicColumnsTableReport;
    use TraitForwardModeReport;
    use TraitModifyDataToExcelReport;
    use TraitUserCompanyTree;
    use TraitLegendReport;

    protected $groupBy = 'name_render';
    protected $groupByLength = 1;
    protected $maxH = 50;

    public function getSqlStr($params)
    {
        $sql = "SELECT 
        wpus.name AS user_workplace,
        tb3.*,
        total_overtime_hours AS _total_overtime_hours,
        LAG(remaining_allowed_ot_hours_year, 1, 200) OVER (PARTITION BY years_month, user_id ORDER BY year_months) AS maximum_allowed_ot_hours_year,
        (40) AS maximum_allowed_ot_hours,
        ROUND(40 - total_overtime_hours, 2) AS remaining_allowed_ot_hours
        FROM (
            SELECT 
                wp.name AS ot_workplace_name,
                rgt_ot.*,
                us.name0 AS name_render,
                200 - SUM(total_overtime_hours) OVER (PARTITION BY employee_id, years_month ORDER BY year_months ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS remaining_allowed_ot_hours_year
            FROM (
                SELECT 
                    us.workplace AS user_workplace_id,
                    otr.workplace_id AS ot_workplace_id,
                    uscate.name AS user_category_name,
                    uscate.description AS user_category_desc,
                    otline.employeeid AS employee_id,
                    if(day(otline.ot_date) BETWEEN 1 AND 25, substr(SUBSTR(otline.ot_date,1,20), 1,4), substr(DATE_ADD(SUBSTR(otline.ot_date,1,20), INTERVAL 1 MONTH),1,4)) AS years_month,
                    SUM(otline.total_time) AS total_overtime_hours,
                    us.id AS user_id,
                    if(day(otline.ot_date) BETWEEN 1 AND 25, substr(SUBSTR(otline.ot_date,1,20), 1,7), substr(DATE_ADD(SUBSTR(otline.ot_date,1,20), INTERVAL 1 MONTH),1,7)) AS year_months
                FROM 
                    users us, 
                    hr_overtime_request_lines otline, 
                    hr_overtime_requests otr, 
                    user_categories uscate
                WHERE 1 = 1
                    AND us.deleted_at IS  NULL
                	AND otline.deleted_at IS NULL
                	AND otr.deleted_at IS NULL
                	AND uscate.deleted_at IS NULL
                    AND otline.user_id = us.id";
        if (!CurrentUser::isAdmin()) {
            $treeData = $this->getDataByCompanyTree();
            $userIds = array_column($treeData, 'id');
            if (!count($userIds)) return "";
            $strUserIds = '(' . implode(',', $userIds) . ')';
            $sql .= "\n AND otline.user_id IN $strUserIds";
        }
        $sql .= "\n AND otline.hr_overtime_request_id = otr.id
                    AND uscate.id = us.category
                    AND otline.hr_overtime_request_id = otr.id
                    AND otr.status LIKE 'approved'";
        if (isset($params['user_id'])) $sql .= "\n AND us.id = '{{user_id}}'";
        if (isset($params['workplace_id'])) $sql .= "\n AND us.workplace = '{{workplace_id}}'";
        $sql .= "\n GROUP BY 
                    user_id, employee_id, 
                    year_months, 
                    years_month,
                    ot_workplace_id,
                    user_category_name,
                    user_category_desc
            ) AS rgt_ot 
            JOIN workplaces wp ON wp.id = rgt_ot.ot_workplace_id";

        if (isset($params['ot_workplace_id'])) $sql .= "\n AND wp.id = '{{ot_workplace_id}}'";
        $sql .= "\n JOIN users us ON us.id = rgt_ot.user_id) AS tb3
                    JOIN workplaces wpus ON tb3.user_workplace_id = wpus.id";

        if (isset($params['month'])) $sql .= "\n AND year_months = {{month}}";
        $sql .= "\n ORDER BY name_render, employee_id,  year_months DESC";
        return $sql;
    }


    // Table
    public function getTableColumns($dataSource, $params)
    {
        // dump($dataSource);
        return [
            [
                'title' => 'OT Workplace',
                "dataIndex" => "ot_workplace_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Team",
                "dataIndex" => "user_category_name",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Employee ID",
                "dataIndex" => "employee_id",
                "align" => 'left',
                "width" => 150,
            ],
            [
                "title" => "Full Name",
                "dataIndex" => "name_render",
                "align" => 'left',
                "width" => 230,
            ],
            [
                "dataIndex" => "user_workplace",
                "align" => 'left',
                "width" => 150,

            ],
            [
                "title" => "Months",
                "dataIndex" => "year_months",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Maximum Allowed OT Hours (Month)",
                "dataIndex" => "maximum_allowed_ot_hours",
                "align" => "right",
                "width" => 150,
            ],
            [

                "title" => "Total Overtime Hours (Month)",
                "dataIndex" => "total_overtime_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Remaining Allowed OT Hours (Month)",
                "dataIndex" => "remaining_allowed_ot_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Years",
                "dataIndex" => "years_month",
                "align" => "right",
                "width" => 150,
            ],

            [
                "title" => "Maximum Allowed OT Hours (Year)",
                "dataIndex" => "maximum_allowed_ot_hours_year",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Total Overtime Hours (Month)",
                "dataIndex" => "_total_overtime_hours",
                "align" => "right",
                "width" => 150,
            ],
            [
                "title" => "Remaining Allowed OT Hours (Year)",
                "dataIndex" => "remaining_allowed_ot_hours_year",
                "align" => "right",
                "width" => 150,
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
                'title' => 'User Workplace',
                'dataIndex' => 'workplace_id',
                'allowClear' => true,
            ],
            [
                'dataIndex' => 'month',
                'allowClear' => true,
            ],
            [
                'title' => 'User',
                'dataIndex' => 'user_id',
                'allowClear' => true,
            ],
        ];
    }

    private function wrapValueInObjectWithCellColor($percent, $value)
    {

        switch (true) {

            case $percent <= 0:
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
        $statuses = [
            [
                'color' => 'red',
                'color_index' => '400',
                'title' => '< 0%',
            ],
            [
                'color' => 'pink',
                'color_index' => '400',
                'title' => '0% to < 25%',
            ],
            [
                'color' => 'orange',
                'color_index' => '400',
                'title' => '25% to < 50%',
            ],
            [
                'color' => 'yellow',
                'color_index' => '400',
                'title' => '50% to < 75%',
            ],
            [
                'color' => 'green',
                'color_index' => '400',
                'title' => '>= 75%',
            ]
        ];

        $legendData = [
            'legend_title' => 'Remaining Allowed OT Hours Legend',
            'dataSource' => $statuses,
            'legend_col' => 5
        ];
        return $this->createLegendData($legendData);
    }

    protected function enrichDataSource($dataSource, $params)
    {

        // $dataSource = $this->getDataByCompanyTree($dataSource);
        // $type = Str::singular($this->getType());
        foreach ($dataSource as $key => $value) {

            // display colors for total_overtime_hours
            $remainingAllowedOTHoursMonth = ($value->remaining_allowed_ot_hours * 1);
            $remainingAllowedOTHoursYear = $value->remaining_allowed_ot_hours_year * 1;

            // dump($remainingAllowedOTHoursMonth);
            $percentOTMonth = $remainingAllowedOTHoursMonth / 40 * 100;
            $percentOTYear = $remainingAllowedOTHoursYear / 200 * 100;

            $param = '?user_id=' . $value->user_id . '&' . 'month=' . $value->year_months . '&' . 'ot_workplace_id=' . $value->ot_workplace_id;
            $reAllowedOTHoursMonth = $this->wrapValueInObjectWithCellColor($percentOTMonth, $remainingAllowedOTHoursMonth);
            $reAllowedOTHoursYear = $this->wrapValueInObjectWithCellColor($percentOTYear, $remainingAllowedOTHoursYear);

            $dataSource[$key]->remaining_allowed_ot_hours = $reAllowedOTHoursMonth ?? $value->remaining_allowed_ot_hours;
            $dataSource[$key]->remaining_allowed_ot_hours_year = $reAllowedOTHoursYear;

            // dd($dataSource[0]);
            $hrefForward = route("report-hr_overtime_request_020") . $param;
            $dataSource[$key]->total_overtime_hours = (object)[
                'value' => $value->total_overtime_hours,
                'cell_href' => $hrefForward,
                'cell_class' => 'text-blue-700',
            ];

            $dataSource[$key]->user_category_name = (object)[
                'value' => $value->user_category_name,
                'cell_title' => $value->user_category_desc
            ];
            // display name/description for total_overtime_hours
            $dataSource[$key]->employee_id = (object)[
                'value' => $value->employee_id,
                'cell_title' => 'User ID: ' . $value->user_id,
            ];
        }
        return $dataSource;
    }
}
