<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Utils\Support\Report;
use App\View\Components\Reports\ModeParams\ParamUserId;

class Kanban_task_010 extends Report_ParentReport2Controller
{
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = true;
    protected $pageLimit = 20;
    protected $maxH = 80;
    protected $mode='010';


    private function getUserOnOwner(){
        $ins = new ParamUserId();
        $ids = array_column($ins->getDataSource(), 'id');
        return $ids;
    }

    public function getSqlStr($params)
    {
        $userIds = $this->getUserOnOwner();
        $strUserIds = implode(",", $userIds);
        $valOfParams = $this->generateValuesFromParamsReport($params);
        ['start' => $start, 'end' => $end] = $valOfParams['picker_date'];
        // dd($valOfParams);

            $sql = "SELECT
            kt.id AS kanban_task_id,
            NOW() AS date_current,
            kt.name AS kanban_task_name,
            us.full_name AS full_name,
            us.employeeid AS employee_id,
            us.workplace AS workplace_id,
            wp.name AS workplace_name,
            dp.name AS department_name,
            dp.id AS department_id,
            ktg.id AS kanban_task_group_id,
            ktg.name AS kanban_task_group_name,
            ktc.id AS kanban_cluster_id,
            ktc.name AS kanban_cluster_name,
            ktp.id AS kanban_task_page_id,
            ktp.name AS kanban_task_pages_name,
            ktt.excluded_seconds/60 AS excluded_seconds,
            -- IF(ktt.elapsed_seconds/60, ktt.elapsed_seconds,TIME_TO_SEC(TIMEDIFF(NOW(), ktt.start_at))/60)  AS elapsed_seconds,
            ktt.elapsed_seconds/60  AS elapsed_seconds,
            ktt.start_at AS start_at,
            ktt.end_at AS end_at,
            TIME_TO_SEC(TIMEDIFF(ktt.end_at, ktt.start_at))/60 AS working_minutes
            FROM kanban_tasks kt
            LEFT JOIN users us ON kt.assignee_1 = us.id AND kt.assignee_1 IN ($strUserIds) AND us.id IN ($strUserIds)
            LEFT JOIN departments dp ON dp.id = us.department
            LEFT JOIn kanban_task_groups ktg ON ktg.id = kt.kanban_group_id
            LEFT JOIN kanban_task_transitions ktt ON ktt.kanban_task_id = kt.id #AND ktt.kanban_group_id = ktg.id
            LEFT JOIN kanban_task_clusters ktc ON ktc.id = ktg.kanban_cluster_id
            LEFT JOIN kanban_task_pages ktp ON ktp.id = ktc.kanban_page_id
            LEFT JOIN workplaces wp ON wp.id = us.workplace
            WHERE 1 = 1";
            if (Report::checkValueOfField($valOfParams, 'workplace_id')) $sql .= "\n AND wp.id = {$valOfParams['workplace_id']}";
            if (Report::checkValueOfField($valOfParams, 'kanban_task_name_id')) $sql .= "\n AND  AND kt.id  = {$valOfParams['kanban_task_name_id']}";
            if (Report::checkValueOfField($valOfParams, 'user_id')) $sql .= "\n AND us.id= {$valOfParams['user_id']}";
            if ($start) $sql .= "\n AND DATE_FORMAT(ktt.start_at, '%Y-%m-%d') >= '$start'";
            if ($end) $sql .= "\n AND DATE_FORMAT(ktt.end_at, '%Y-%m-%d') <= '$end' #OR ktt.end_at IS NULL
            ORDER BY ktt.start_at DESC";
        return $sql;
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [
            [
                'title' => 'Full Name',
                'dataIndex' => 'full_name',
                'width' => 200
            ],
            [
                'title' => 'Employee ID',
                'dataIndex' => 'employee_id',
                'width' => 70
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'workplace_name',
                'width' => 80
            ],
            [
                'title' => 'Team',
                'dataIndex' => 'department_name',
                'width' => 80
            ],
            [
                'title' => 'Task Group',
                'dataIndex' => 'kanban_task_group_name',
                'width' => 200
            ],
            [
                'title' => 'Cluster',
                'dataIndex' => 'kanban_cluster_name',
                'width' => 150
            ],
            [
                'title' => 'Page',
                'dataIndex' => 'kanban_task_pages_name',
                'width' => 100
            ],
            [
                'title' => 'Task Name',
                'dataIndex' => 'kanban_task_name',
                'width' => 400
            ],
            [
                'title' => 'Start',
                'dataIndex' => 'start_at',
                'width' => 140
            ],
            [
                'title' => 'End',
                'dataIndex' => 'end_at',
                'width' => 140
            ],
            [
                'title' => 'Excluded <br/>(min)',
                'dataIndex' => 'excluded_seconds',
                'width' => 140,
                'align'=> 'right',
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'Elapsed <br/>(min)',
                'dataIndex' => 'elapsed_seconds',
                'width' => 140,
                'align'=> 'right',
                'footer' => 'agg_sum',
            ],
            [
                'title' => 'Working Time <br/>(min)',
                'dataIndex' => 'working_minutes',
                'width' => 140,
                'align'=> 'right',
                'footer' => 'agg_sum',
            ],
        ];
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
                'title' => 'Date',
                'dataIndex' => 'picker_date',
                'renderer' => 'picker_date',
                "allowClear" => false,
                'validation' => 'date_format:d/m/Y',
            ],
            [
                "title" => "User",
                "dataIndex" => "user_id",
                "allowClear" => true,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_id",
                "allowClear" => true,
            ],
            [
                "title" => "Task",
                "dataIndex" => "kanban_task_name_id",
                "allowClear" => true,
            ],
            
        ];
    }

}
