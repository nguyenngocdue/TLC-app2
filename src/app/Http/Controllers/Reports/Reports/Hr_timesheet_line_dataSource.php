<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\TraitCreateSQL;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\PivotReport;
use Illuminate\Support\Facades\DB;

class Hr_timesheet_line_dataSource extends Controller

{
    use TraitDynamicColumnsTableReport;
    use TraitCreateSQL;

    protected $maxH = 50;
    protected $mode = '100';
    #protected $rotate45Width = 300;
    protected $libPivotFilters;

    public function getSqlStr($params)
    {
        $pickerDate = $params['picker_date'] ?? DateReport::defaultPickerDate();
        [$startDate, $endDate] = DateReport::explodePickerDate($pickerDate, 'Y-m-d');
        $sql = "SELECT
                    DATE(tsl.start_time) AS time_sheet_start_time,
                    SUBSTRING(tsl.start_time, 1, 7) AS month,
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
                    hr_timesheet_lines tsl, users us 
                    WHERE 1 = 1
                            AND us.id = tsl.user_id";

        if (isset($params['user_id']) && is_array($params['user_id'])) {
            $ids = implode(',', $params['user_id']);
            if ($ids) $sql .= "\n AND tsl.user_id IN ($ids)";
        } else {
            if (isset($params['user_id']) &&  $params['user_id']) {
                $id = $params['user_id'];
                $sql .= "\n AND tsl.user_id = $id";
            }
        }
        $sql .= "\n 
                    AND DATE(tsl.start_time) BETWEEN '$startDate' AND '$endDate'
                    #AND tsl.sub_project_id IN (82, 21)
                GROUP BY
                    time_sheet_start_time,
                    pj_task_id,
                    sub_project_id,
                    user_id,
                    discipline_id,
                    lod_id,
                    department_id,
                    type_id,
                    category_id,
                    staff_id,
                    workplace_id,
                    month
                ORDER BY
                    user_id";
        return $sql;
    }

    public function getDataSource($params)
    {
        $sql = $this->getSql($params);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select($sql);
        $collection = collect($sqlData);
        return $collection;
    }
}
