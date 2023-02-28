<?php

namespace App\Http\Controllers\Reports\Registers;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Workplace;
use Illuminate\Support\Facades\DB;

class Hr_overtime_request_line extends Report_ParentController
{
    use TraitReport;
    public function getSqlStr($urlParams)
    {
        // dd($urlParams);
        $sql = "
        SELECT otTb.*, wpus.name AS user_workplace
        FROM (SELECT 
            wp.name AS workplace, rgt_ot.*
            FROM (SELECT 
                us.workplace AS user_workplace_id
                ,otr.workplace_id AS ot_workplace_id
                ,us.first_name AS first_name
                ,us.last_name AS last_name
                ,uscate.name AS user_category_name
                ,uscate.description AS user_category_desc
                ,otline.employeeid AS employeeid
                ,us.full_name AS member_name
                ,SUBSTR(otline.ot_date,1,7) AS year_months
                ,SUM(otline.total_time) AS total_overtime_hours
                ,(60) AS maximum_allowed_ot_hours
                ,(60-SUM(otline.total_time) ) AS remaining_allowed_ot_hours
                    FROM users us, hr_overtime_request_lines otline, hr_overtime_requests otr, user_categories uscate
                    WHERE 1 = 1
                    AND otline.user_id = us.id
                    AND otline.hr_overtime_request_id = otr.id
                    AND uscate.id = us.category";
        if (isset($urlParams['user_id'])) $sql .= "\n AND us.id = '{{user_id}}'";

        if (isset($urlParams['months'])) $sql .= "\n AND SUBSTR(otline.ot_date,1,7) = '{{months}}'";
        $sql .= "\n GROUP BY user_id, employeeid, year_months, workplace_id) AS rgt_ot \n";

        $sql .= "JOIN workplaces wp ON wp.id = rgt_ot.ot_workplace_id";
        if (isset($urlParams['ot_workplace_id'])) $sql .= "\n AND wp.id = '{{ot_workplace_id}}'";
        $sql .= "\n ORDER BY workplace, user_category_name, first_name, last_name, employeeid, year_months DESC )AS otTb, workplaces wpus
                    WHERE 1 = 1
                    AND otTb.user_workplace_id = wpus.id ";
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
                "title" => "Team",
                "dataIndex" => "user_category_name",
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
                "dataIndex" => "user_workplace",
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
    protected function enrichDataSource($dataSource, $urlParams)
    {
        $isNullParams = $this->isNullUrlParams($urlParams);
        if ($isNullParams) {
            $dataSource->setCollection(collect([]));
            return $dataSource;
        };

        if (!count(array_values($urlParams))) return [];
        foreach ($dataSource as $key => $value) {

            // display name/description for total_overtime_hours
            $teamName = $value->user_category_name;
            $teamDesc = $value->user_category_desc;
            $strTeam = "<span title='$teamDesc'>$teamName</span>";
            $dataSource[$key]->user_category_name = $strTeam;


            // display colors for total_overtime_hours
            $hours = $value->total_overtime_hours * 1;
            $strHour = "";
            if ($hours > 60) {
                $strHour = "<div class='bg-red-500'>$hours</i></div>";
            }
            if (40 < $hours && $hours <= 60) {
                $strHour = "<div class='bg-yellow-400'>$hours</i></div>";
            }
            if (20 < $hours && $hours <= 40) {
                $strHour = "<div class='bg-green-400'>$hours</i></div>";
            }
            if ($hours <= 20) {
                $strHour = "<div class='bg-white'>$hours</i></div>";
            }
            $dataSource[$key]->total_overtime_hours = $strHour;
        }


        // dd($dataSource);
        return $dataSource;
    }
}
