<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Helpers\Helper;
use App\Http\Controllers\Reports\Report_ParentController;
use App\Models\Workplace;
use App\Utils\Support\Report;

class Hr_overtime_request_line extends Report_ParentController
{
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
        $sql = " SELECT 
            wp.name AS workplace, rgt_ot.*
            ,MONTH(rgt_ot.overtime_date) AS months
            , ('') AS team
            ,CONCAT(YEAR(rgt_ot.overtime_date), -  FORMAT(MONTH(rgt_ot.overtime_date), 'MM')) AS overtime_year_month
            FROM (SELECT 
                otline.id AS id_request_line
                ,otline.user_id AS user_id
                ,us.workplace AS workplace_id
                ,us.first_name AS first_name
                ,us.last_name AS last_name
                ,otline.employeeid AS employeeid
                ,us.full_name AS member_name
                ,otline.ot_date AS overtime_date
                ,otline.total_time AS total_overtime_hours
                ,(60) AS maximum_allowed_OT_hours
                ,(60-otline.total_time ) AS remaining_allowed_OT_hours
                    FROM users us, hr_overtime_request_lines otline
                    WHERE 1 = 1
                    AND otline.user_id = us.id";
        if (isset($urlParams['months'])) $sql .= "\n AND MONTH(otline.ot_date) = '{{months}}'";
        $sql .= ") AS rgt_ot \n";

        $sql .= "JOIN workplaces wp ON wp.id = rgt_ot.workplace_id";
        if (isset($urlParams['workplace_id'])) $sql .= "\n AND wp.id = '{{workplace_id}}'";
        $sql .= "\n ORDER BY workplace, team, first_name, last_name, employeeid, months";
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
                "dataIndex" => "months",
                "align" => "right",
            ],
            [
                "dataIndex" => "maximum_allowed_OT_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "total_overtime_hours",
                "align" => "right",
            ],
            [
                "dataIndex" => "remaining_allowed_OT_hours",
                "align" => "right",
            ],
        ];
    }


    public function getDataForModeControl($dataSource)
    {
        // dd($dataSource);
        $workplaces = ['workplace_id' => Workplace::get()->pluck('name', 'id')->toArray()];
        $months = ['months' => array_unique(array_column($dataSource->items(),  'overtime_year_month', 'months'))];
        return array_merge($workplaces, $months);
    }
    protected function enrichDataSource($dataSource, $urlParams)
    {
        // dd($dataSource);
        if (!count(array_values($urlParams))) return [];
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
        // dd($dataSource);
        return $dataSource;
    }
}
