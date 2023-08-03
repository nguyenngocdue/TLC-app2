<?php

namespace App\Warehouse;

use App\Utils\Support\Tree\BuildTree;
use Illuminate\Support\Facades\DB;

class Wh_user_sub_project_task extends Wh_parent
{
    public function getSqlStr($userIds, $month)
    {
        $strUserIds = '(' . implode(',', $userIds) . ')';
        $sql = " SELECT 
                    #tsl.id AS tsl_id
                    tsl.user_id AS user_id
                    ,CONCAT(SUBSTR(tsl.start_time, 1, 7), '-', '01') AS month
                    ,tsl.sub_project_id AS sub_project_id
                    ,tsl.duration_in_min AS value
                    ,tsl.task_id AS task_id
                    
                    FROM  hr_timesheet_lines tsl
                    WHERE 1 = 1
                    AND tsl.user_id IN $strUserIds
                    AND SUBSTR(tsl.start_time, 1, 7) = '$month';
        ";
        // dump($sql);
        return $sql;
    }
}
