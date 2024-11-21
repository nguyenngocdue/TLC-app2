<?php

namespace App\Http\Controllers\Reports\Reports;

use App\Http\Controllers\Reports\Report_ParentReport2Controller;
use App\Models\Kanban_task_transition;
use App\Utils\Support\Report;
use App\View\Components\Reports\ModeParams\ParamUserId;

class Kanban_task_020 extends Report_ParentReport2Controller
{
    protected $typeView = 'report-pivot';
    protected $tableTrueWidth = true;
    protected $pageLimit = 10;
    protected $maxH = 50 * 16;
    protected $mode = '020';


    private function getUserOnOwner()
    {
        $ins = new ParamUserId();
        $ids = array_column($ins->getDataSource(), 'id');
        return $ids;
    }

    private function getActiveIdTasks()
    {
        $ids = array_values(Kanban_task_transition::Where('deleted_by', null)->pluck('kanban_task_id')->unique()->toArray());
        return $ids;
    }

    public function getSqlStr($params)
    {
        $userIds = $this->getUserOnOwner();
        $strUserIds = implode(",", $userIds);
        $valOfParams = $this->generateValuesFromParamsReport($params);
        ['start' => $start, 'end' => $end] = $valOfParams['picker_date'];

        $activeIdTasks = $this->getActiveIdTasks();
        $strActiveIdTasks = implode(",", $activeIdTasks);
        $strActiveIdTasks = isset($params["kanban_task_name_id"]) && $params["kanban_task_name_id"] ? implode(",", $params["kanban_task_name_id"]) : $strActiveIdTasks;
        $valOfParams['kanban_task_name_id'] = $strActiveIdTasks;


        $sql = " SELECT elapseTimeTb.*, minmaxTb.*,
                        TIME_TO_SEC(TIMEDIFF(kanban_task_created_at, index_waiting_time)) AS waiting_time_second
                        FROM (SELECT
                            kanban_task_id, kanban_task_name,kanban_task_deleted_by,kanban_task_created_at,
                            kanban_task_page_id, kanban_task_page_name,
                            kanban_task_cluster_id, kanban_task_cluster_name,
                            kanban_task_bucket_id, kanban_task_bucket_name,
                            user_id, full_name, employee_id, workplace_id, workplace_name, department_id, department_name,
                            ROUND(SUM(detailTb.elapsed_seconds),2) AS total_elapsed_second,
                            GROUP_CONCAT(DISTINCT kanban_task_group_owner_id) AS kanban_task_group_owner_id

                            -- ROUND(SUM(detailTb.elapsed_seconds) AS total_elapsed_second,
                            -- ROUND(SUM(CASE WHEN detailTb.time_counting_type = 2 THEN detailTb.elapsed_seconds ELSE 0 END),2) AS total_took_second,
                            -- ROUND(SUM(CASE WHEN detailTb.time_counting_type <> 1  OR detailTb.time_counting_type IS NULL THEN detailTb.elapsed_seconds ELSE 0 END),2) AS total_elapsed_downtime_second
                        FROM (SELECT
                            ktp.id AS kanban_task_page_id,
                            ktp.name AS kanban_task_page_name,
                    
                            ktc.id AS kanban_task_cluster_id,
                            ktc.name AS kanban_task_cluster_name,
                    
                            ktb.id AS kanban_task_bucket_id,
                            ktb.name AS kanban_task_bucket_name,
                    
                            kt.id AS kanban_task_id,
                            kt.name AS kanban_task_name,

                            kt.created_at AS kanban_task_created_at,
                            
                            IF(kt.deleted_by,'YES', 'NO') AS kanban_task_deleted_by,
                            kt.deleted_at AS kanban_task_deleted_at,
                    
                            us.full_name AS full_name,
                            us.id AS user_id,
                            us.employeeid AS employee_id,
                            us.workplace AS workplace_id,
                            wp.name AS workplace_name,
                            dp.name AS department_name,
                            dp.id AS department_id,            
                            ktg.id AS kanban_task_group_id,
                            ktg.assignee_1 AS kanban_task_group_owner_id,
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
                            WHERE 1 = 1 ";
        #AND us.id= 444
        #AND ktc.id  = 15
        #AND kt.id  = 144
        #AND DATE_FORMAT(ktt.start_at, '%Y-%m-%d') >= '2023-01-21'
        #AND DATE_FORMAT(ktt.end_at, '%Y-%m-%d') <= '2024-01-23'";

        if (Report::checkValueOfField($valOfParams, 'kanban_task_name_id')) $sql .= "\n AND kt.id  IN ({$valOfParams['kanban_task_name_id']})";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_cluster')) $sql .= "\n AND ktc.id  = {$valOfParams['kanban_task_cluster']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_group')) $sql .= "\n AND ktg.id  = {$valOfParams['kanban_task_group']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_page')) $sql .= "\n AND ktp.id  = {$valOfParams['kanban_task_page']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_bucket')) $sql .= "\n AND ktb.id  = {$valOfParams['kanban_task_bucket']}";

        if (Report::checkValueOfField($valOfParams, 'workplace_id')) $sql .= "\n AND wp.id = {$valOfParams['workplace_id']}";
        if (Report::checkValueOfField($valOfParams, 'department_id')) $sql .= "\n AND dp.id  = {$valOfParams['department_id']}";
        if (Report::checkValueOfField($valOfParams, 'user_id')) $sql .= "\n AND us.id= {$valOfParams['user_id']}";

        if ($start) $sql .= "\n AND DATE_FORMAT(ktt.start_at, '%Y-%m-%d') >= '$start'";
        if ($end) $sql .= "\n AND DATE_FORMAT(ktt.end_at, '%Y-%m-%d') <= '$end' OR kt.id  IN ({$valOfParams['kanban_task_name_id']}) AND ktt.end_at IS NULL";

        $sql .= "\n ) AS detailTb
                            WHERE 1 = 1";

        if (Report::checkValueOfField($valOfParams, 'kanban_task_name_id')) $sql .= "\n AND detailTb.kanban_task_id  IN ({$valOfParams['kanban_task_name_id']})";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_cluster')) $sql .= "\n AND detailTb.kanban_task_cluster_id  = {$valOfParams['kanban_task_cluster']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_group')) $sql .= "\n AND detailTb.kanban_task_group_id  = {$valOfParams['kanban_task_group']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_page')) $sql .= "\n AND detailTb.kanban_task_page_id  = {$valOfParams['kanban_task_page']}";
        if (Report::checkValueOfField($valOfParams, 'kanban_task_bucket')) $sql .= "\n AND detailTb.kanban_task_bucket_id  = {$valOfParams['kanban_task_bucket']}";

        if (Report::checkValueOfField($valOfParams, 'workplace_id')) $sql .= "\n AND detailTb.workplace_id = {$valOfParams['workplace_id']}";
        if (Report::checkValueOfField($valOfParams, 'department_id')) $sql .= "\n AND detailTb.department_id  = {$valOfParams['department_id']}";
        if (Report::checkValueOfField($valOfParams, 'user_id')) $sql .= "\n AND detailTb.user_id = {$valOfParams['user_id']}";


        $sql .= "\n GROUP BY detailTb.kanban_task_id, detailTb.kanban_task_page_id, detailTb.kanban_task_cluster_id
                            ) AS elapseTimeTb
                    
                    JOIN 
                    (
                        WITH MinStartTimes AS (
                        SELECT 
                            kanban_task_id, 
                            MIN(start_at) AS MinStart
                        FROM 
                            kanban_task_transitions
                        GROUP BY 
                            kanban_task_id
                        ),
                        MaxStartTimes AS (
                            SELECT 
                                kanban_task_id, 
                                MAX(start_at) AS MaxStart
                            FROM 
                                kanban_task_transitions
                            GROUP BY 
                                kanban_task_id
                        ),
                        MinDetails AS (
                            SELECT 
                                ktt.kanban_task_id, 
                                ktt.kanban_group_id, 
                                ktt.start_at,
                                ktt.end_at
                            FROM 
                                kanban_task_transitions ktt
                            INNER JOIN 
                                MinStartTimes ON ktt.kanban_task_id = MinStartTimes.kanban_task_id AND ktt.start_at = MinStartTimes.MinStart
                        ),
                        MaxDetails AS (
                            SELECT 
                                ktt.kanban_task_id, 
                                ktt.kanban_group_id, 
                                ktt.start_at
                            FROM 
                                kanban_task_transitions ktt
                            INNER JOIN 
                                MaxStartTimes ON ktt.kanban_task_id = MaxStartTimes.kanban_task_id AND ktt.start_at = MaxStartTimes.MaxStart
                        )
                        SELECT 
                            MinDetails.kanban_task_id, 
                            MinDetails.kanban_group_id AS min_kanban_group_id,
                            ktg_min.name AS min_kanban_group_name,
                            MaxDetails.kanban_group_id AS max_kanban_group_id,
                            ktg_max.name AS max_kanban_group_name,
                            MinDetails.start_at AS min_start_at,
                            IF(MinDetails.end_at IS NULL , NOW(), MinDetails.start_at) AS index_waiting_time,
                            MaxDetails.start_at AS temp_end_at
                        FROM 
                            MinDetails
                        JOIN 
                            MaxDetails ON MinDetails.kanban_task_id = MaxDetails.kanban_task_id
                        JOIN 
                            kanban_task_groups ktg_min ON MinDetails.kanban_group_id = ktg_min.id
                        JOIN 
                            kanban_task_groups ktg_max ON MaxDetails.kanban_group_id = ktg_max.id
                    )  AS minmaxTb ON minmaxTb.kanban_task_id = elapseTimeTb.kanban_task_id
                    ORDER BY temp_end_at DESC
                    ";
        return $sql;
    }

    protected function getTableColumns($params, $dataSource)
    {
        return [

            [
                'title' => 'Task Bucket',
                'dataIndex' => 'kanban_task_bucket_name',
                'width' => 140
            ],
            [
                'title' => 'Task Page',
                'dataIndex' => 'kanban_task_page_name',
                'width' => 140
            ],
            [
                'title' => 'Cluster',
                'dataIndex' => 'kanban_task_cluster_name',
                'width' => 150
            ],
            [
                'title' => 'Task',
                'dataIndex' => 'kanban_task_name',
                'width' => 250
            ],
            [
                'title' => 'Created At',
                'dataIndex' => 'kanban_task_created_at',
                'width' => 140
            ],
            [
                'title' => 'index_waiting_time',
                'dataIndex' => 'index_waiting_time',
                'width' => 140
            ],
            [
                'title' => 'Deleted',
                'dataIndex' => 'kanban_task_deleted_by',
                'width' => 100
            ],
            [
                'title' => 'Start At',
                'dataIndex' => 'min_start_at',
                'width' => 140
            ],
            [
                'title' => 'End At <br/> (transient)',
                'dataIndex' => 'temp_end_at',
                'width' => 150
            ],
            [
                'title' => 'Status <br/>(Start At)',
                'dataIndex' => 'min_kanban_group_name',
                'width' => 150
            ],
            [
                'title' => 'Status <br/>(End At)<br/>(transient)',
                'dataIndex' => 'max_kanban_group_name',
                'width' => 150
            ],
            // [
            //     'title' => 'Full Name',
            //     'dataIndex' => 'full_name',
            //     'width' => 200
            // ],
            // [
            //     'title' => 'Employee ID',
            //     'dataIndex' => 'employee_id',
            //     'width' => 200
            // ],
            // [
            //     'title' => 'Workplace',
            //     'dataIndex' => 'workplace_name',
            //     'width' => 150
            // ],
            // [
            //     'title' => 'Department',
            //     'dataIndex' => 'department_name',
            //     'width' => 180
            // ],
            [
                'title' => 'Assignee',
                'dataIndex' => 'kanban_task_group_owner_id',
                'width' => 180
            ],
            [
                'title' => 'Waiting Time',
                'dataIndex' => 'waiting_time_second',
                'width' => 180
            ],
            [
                'title' => 'Total Elapsed Time',
                'dataIndex' => 'total_elapsed_second',
                'width' => 180,
                'align' => 'right',
                'footer' => 'agg_sum',
            ],
            /* [
                'title' => 'Total Downtime',
                'dataIndex' => 'total_elapsed_downtime_second',
                'width' => 180,
                'align' => 'right',
                'footer' => 'agg_sum',
            ] */
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
                "title" => "User",
                "dataIndex" => "user_id",
                "allowClear" => true,
                "hasListenTo" => true,
            ],
            [
                "title" => "Bucket",
                "dataIndex" => "kanban_task_bucket",
                "allowClear" => true,
            ],
            [
                "title" => "Page",
                "dataIndex" => "kanban_task_page",
                "allowClear" => true,
            ],
            [
                "title" => "Cluster",
                "dataIndex" => "kanban_task_cluster",
                "allowClear" => true,
            ],
            [
                "title" => "Task",
                "dataIndex" => "kanban_task_name_id",
                "allowClear" => true,
                "multiple" => true,

            ],
            [
                "title" => "Task Group",
                "dataIndex" => "kanban_task_group",
                "allowClear" => true,
            ],

        ];
    }
    public function changeDataSource($dataSource, $params)
    {
        foreach ($dataSource as &$items) {
            if (!(float)$items->total_elapsed_second) {
                $items->total_elapsed_second = (object)[
                    'value' => 'NYS',
                    'cell_class' => 'bg-blue-50',
                ];
            }
        }
        // dd($dataSource);
        return $dataSource;
    }
}
