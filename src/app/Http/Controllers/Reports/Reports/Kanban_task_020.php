<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Utils\Support\Report;
use App\View\Components\Reports\ModeParams\ParamUserId;

class Kanban_task_020 extends Report_ParentReport2Controller
{
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = true;
    protected $pageLimit = 20;
    protected $maxH = 80;
    protected $mode='020';


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
        // dd($params);

            $sql =" SELECT indexTb.*, indexGroupTb.*
                        FROM (
                            SELECT
                               
                                kanban_task_bucket_id,
                                kanban_task_bucket_name,
                                kanban_task_cluster_name,
                                kanban_task_cluster_id,
                                #GROUP_CONCAT(DISTINCT kanban_task_cluster_id) AS concat_kanban_task_cluster_id,
                                COUNT(DISTINCT kanban_task_cluster_name) AS count_cluster,
                                kanban_task_page_id,
                                kanban_task_pages_name,
                                kanban_task_id,
                                kanban_task_name,
                                user_id,
                                full_name,
                                employee_id,
                                workplace_id,
                                workplace_name,
                                department_id,
                                department_name,
                                ROUND(SUM(CASE WHEN detailTB.time_counting_type = 1 THEN detailTB.elapsed_seconds ELSE 0 END)/60/60,2) AS total_elapsed_second,
                                ROUND(SUM(CASE WHEN detailTB.time_counting_type <> 1 OR detailTB.time_counting_type IS NULL THEN detailTB.elapsed_seconds ELSE 0 END)/60/60,2) AS total_downtime_second
                                FROM (SELECT
                                        
                                        ktp.id AS kanban_task_page_id,
                                        ktp.name AS kanban_task_pages_name,
                        
                                        ktc.id AS kanban_task_cluster_id,
                                        ktc.name AS kanban_task_cluster_name,

                                        ktb.id AS kanban_task_bucket_id,
                                        ktb.name AS kanban_task_bucket_name,
                        
                                        kt.id AS kanban_task_id,
                                        kt.name AS kanban_task_name,
                        
                                        us.full_name AS full_name,
                                        us.id AS user_id,
                                        us.employeeid AS employee_id,
                                        us.workplace AS workplace_id,
                                        wp.name AS workplace_name,
                                        dp.name AS department_name,
                                        dp.id AS department_id,            
                                        ktg.id AS kanban_task_group_id,
                                        ktg.name AS kanban_task_group_name,            
                                        ktg.time_counting_type AS time_counting_type,
                                        ktt.elapsed_seconds AS elapsed_seconds,
                                        ktt.end_at AS end_at
                                        FROM kanban_tasks kt
                                        LEFT JOIN users us ON kt.assignee_1 = us.id
                                        LEFT JOIN departments dp ON dp.id = us.department
                                        LEFT JOIN workplaces wp ON wp.id = us.workplace
                                        
                                        LEFT JOIN kanban_task_transitions ktt ON ktt.kanban_task_id = kt.id
                                        LEFT JOIn kanban_task_groups ktg ON ktg.id = ktt.kanban_group_id  AND ktt.kanban_task_id = kt.id
                                        LEFT JOIN kanban_task_clusters ktc ON ktc.id = ktg.kanban_cluster_id AND ktg.kanban_cluster_id = ktc.id
                                        LEFT JOIN kanban_task_pages ktp ON ktp.id = ktc.kanban_page_id
                                        LEFT JOIN kanban_task_buckets ktb ON ktb.id = ktp.kanban_bucket_id
                                        WHERE 1 = 1
                                            AND kt.kanban_group_id = ktg.id";

                            if (Report::checkValueOfField($valOfParams, 'kanban_task_name_id')) $sql .= "\n AND kt.id  = {$valOfParams['kanban_task_name_id']}";
                            if (Report::checkValueOfField($valOfParams, 'kanban_task_cluster')) $sql .= "\n AND ktc.id  = {$valOfParams['kanban_task_cluster']}";
                            if (Report::checkValueOfField($valOfParams, 'kanban_task_group')) $sql .= "\n AND ktg.id  = {$valOfParams['kanban_task_group']}";
                            if (Report::checkValueOfField($valOfParams, 'kanban_task_page')) $sql .= "\n AND ktp.id  = {$valOfParams['kanban_task_page']}";
                            if (Report::checkValueOfField($valOfParams, 'kanban_task_bucket')) $sql .= "\n AND ktb.id  = {$valOfParams['kanban_task_bucket']}";
                            
                            if (Report::checkValueOfField($valOfParams, 'workplace_id')) $sql .= "\n AND wp.id = {$valOfParams['workplace_id']}";
                            if (Report::checkValueOfField($valOfParams, 'department_id')) $sql .= "\n AND dp.id  = {$valOfParams['department_id']}";
                            if (Report::checkValueOfField($valOfParams, 'user_id')) $sql .= "\n AND us.id= {$valOfParams['user_id']}";
                                        
                            if ($start) $sql .= "\n AND DATE_FORMAT(ktt.start_at, '%Y-%m-%d') >= '$start'";
                            if ($end) $sql .= "\n AND DATE_FORMAT(ktt.end_at, '%Y-%m-%d') <= '$end'";

                            $sql .= "\n ) AS detailTB
                                GROUP BY kanban_task_page_id, detailTB.kanban_task_id ,kanban_task_cluster_id
                        ) AS indexTb
                                JOIN (SELECT
                            ktt2.kanban_task_id AS kanban_task_id, 
                            ktt2.kanban_group_id AS kanban_group_id,
                            ktg2.name AS current_kanban_group_name,
                            ktg2.id AS current_kanban_group_id,
                            MAX(IF(ktt2.end_at IS NULL,  ktt2.start_at, NULL)) AS start_at,
                            MIN(ktt2.start_at) AS begin_at,
                            MAX(ktt2.end_at) AS end_at
                        FROM kanban_task_transitions ktt2
                        JOIN kanban_task_groups ktg2 ON ktt2.kanban_group_id = ktg2.id
                        WHERE ktt2.end_at IS NULL
                        GROUP BY kanban_task_id, kanban_group_id ) AS indexGroupTb ON  indexGroupTb.kanban_task_id = indexTb.kanban_task_id";
        return $sql;
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [
           
            [
                'title' => 'Task Page',
                'dataIndex' => 'kanban_task_pages_name',
                'width' => 200
            ],
            [
                'title' => 'Task Bucket',
                'dataIndex' => 'kanban_task_bucket_name',
                'width' => 200
            ],
            [
                'title' => 'Cluster',
                'dataIndex' => 'kanban_task_cluster_name',
                'width' => 200
            ],
            [
                'title' => 'Task',
                'dataIndex' => 'kanban_task_name',
                'width' => 200
            ],
            [
                'title' => 'Start',
                'dataIndex' => 'begin_at',
                'width' => 180
            ],
            [
                'title' => 'End',
                'dataIndex' => 'end_at',
                'width' => 180
            ],
            [
                'title' => 'Current Status',
                'dataIndex' => 'current_kanban_group_name',
                'width' => 200
            ],
            [
                'title' => 'Full Name',
                'dataIndex' => 'full_name',
                'width' => 200
            ],
            [
                'title' => 'Employee ID',
                'dataIndex' => 'employee_id',
                'width' => 200
            ],
            [
                'title' => 'Workplace',
                'dataIndex' => 'workplace_name',
                'width' => 200
            ],
            [
                'title' => 'Department',
                'dataIndex' => 'department_name',
                'width' => 200
            ],
            [
                'title' => 'Total Elapsed <br/> (hours)',
                'dataIndex' => 'total_elapsed_second',
                'width' => 200,
                'align' => 'right'
            ],
            [
                'title' => 'Total Downtime <br/> (hours)',
                'dataIndex' => 'total_downtime_second',
                'width' => 200,
                'align' => 'right'
            ]
        ];
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        return [
            [
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
                "title" => "Department",
                "dataIndex" => "department_id",
                "allowClear" => true,
            ],
            [
                "title" => "Workplace",
                "dataIndex" => "workplace_id",
                "allowClear" => true,
            ],
            [
                "title" => "Task Cluster",
                "dataIndex" => "kanban_task_cluster",
                "allowClear" => true,
            ],
            [
                "title" => "Task Bucket",
                "dataIndex" => "kanban_task_bucket",
                "allowClear" => true,
            ],
            [
                "title" => "Task Group",
                "dataIndex" => "kanban_task_group",
                "allowClear" => true,
            ],
            [
                "title" => "Task Page",
                "dataIndex" => "kanban_task_page",
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
