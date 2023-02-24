<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Workplace;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Hr_overtime_request_line extends Report_ParentController
{
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
        $sql = " SELECT 
            wp.name AS workplace, rgt_ot.*
            , ('') AS team
            ,year_months
            FROM (SELECT 
            otline.user_id AS user_id
                ,otr.workplace_id AS workplace_id
                ,us.first_name AS first_name
                ,us.last_name AS last_name
                ,otline.employeeid AS employeeid
                ,us.full_name AS member_name
                ,SUBSTR(otline.ot_date,1,7) AS year_months
                ,SUM(otline.total_time) AS total_overtime_hours
                ,(60) AS maximum_allowed_ot_hours
                ,(60-SUM(otline.total_time) ) AS remaining_allowed_ot_hours
                    FROM users us, hr_overtime_request_lines otline, hr_overtime_requests otr
                    WHERE 1 = 1
                    AND otline.user_id = us.id
                    AND otline.hr_overtime_request_id = otr.id";
        if (isset($urlParams['months'])) $sql .= "\n AND SUBSTR(otline.ot_date,1,7) = '{{months}}'";
        $sql .= "\n GROUP BY user_id, employeeid, year_months, workplace_id) AS rgt_ot \n";

        $sql .= "JOIN workplaces wp ON wp.id = rgt_ot.workplace_id";
        if (isset($urlParams['workplace_id'])) $sql .= "\n AND wp.id = '{{workplace_id}}'";
        $sql .= "\n ORDER BY workplace, team, first_name, last_name, employeeid, year_months DESC";
        return $sql;
    }

    public function getTableColumns($dataSource)
    {
        // dump($dataSource);
        return [
            [
                "dataIndex" => "workplace",
                "align" => 'left'
            ],
            [
                "dataIndex" => "user_id",
                "align" => 'left'
            ],
            [
                "dataIndex" => "team",
                "align" => 'left'
            ],
            [
                "dataIndex" => "employeeid",
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
                "title" => "Months",
                "dataIndex" => "year_months",
                "align" => "right",
            ],
            [
                "title" => "Maximum Allowed OT Hours",
                "dataIndex" => "maximum_allowed_ot_hours",
                "align" => "right",
            ],
            [

                "dataIndex" => "total_overtime_hours",
                "align" => "right",
            ],
            [
                "title" => "Remaining Allowed OT Hours",
                "dataIndex" => "remaining_allowed_ot_hours",
                "align" => "right",
            ],
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

    public function getDataForModeControl($dataSource)
    {
        $workplaces = ['workplace_id' => Workplace::get()->pluck('name', 'id')->toArray()];
        $sqlData = $this->getAllMonths();
        $dt = array_column($sqlData, 'year_months');
        $months = ['months' => array_combine($dt, $dt)];
        return array_merge($workplaces, $months);
    }
    protected function enrichDataSource($dataSource, $urlParams)
    {
        $isAllNUll = count(array_filter($urlParams, fn ($value) => !is_null($value))) <= 0;
        if ($isAllNUll) return [];
        foreach ($dataSource as $key => $value) {
            $hours = $value->total_overtime_hours * 1;
            if ($hours > 60) {
                $str = "<div class='bg-red-500'>$hours</i></di>";
                $dataSource[$key]->total_overtime_hours = $str;
            }
            if (40 < $hours && $hours <= 60) {
                $str = "<div class='bg-yellow-400'>$hours</i></di>";
                $dataSource[$key]->total_overtime_hours = $str;
            }
            if (20 < $hours && $hours <= 40) {
                $str = "<div class='bg-green-400'>$hours</i></di>";
                $dataSource[$key]->total_overtime_hours = $str;
            }
            if ($hours <= 20) {
                $str = "<div class='bg-white'>$hours</i></di>";
                $dataSource[$key]->total_overtime_hours = $str;
            }
        }
        return $dataSource;
    }
}
