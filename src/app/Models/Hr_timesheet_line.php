<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_timesheet_line extends ModelExtended
{
    protected $fillable = [
        'timesheetable_type', 'timesheetable_id', 'ts_date', 'ts_hour', 'project_id', 'sub_project_id',
        'discipline_id', 'task_id', 'sub_task_id', 'work_mode_id', 'owner_id',
        'order_no', 'id', 'remark', 'status', 'lod_id', 'prod_routing_id'
    ];
    public $nameless = true;

    public $eloquentParams = [
        // "getHrTs" => ['belongsTo', Hr_timesheet::class, 'hr_timesheet_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getTask" => ['belongsTo', Pj_task::class, "task_id"],
        "getSubTask" => ['belongsTo', Pj_sub_task::class, "sub_task_id"],
        "getDiscipline" => ['belongsTo', User_discipline::class, "discipline_id"],
        "getLod" => ['belongsTo', Term::class, "lod_id"],

        "timesheetable" => ['morphTo', Hr_timesheet_line::class, 'timesheetable_type', 'timesheetable_id'],
    ];

    public function timesheetable()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    // public function getHrTs()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    public function getRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLod()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkMode()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTask()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubTask()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            // ['dataIndex' => 'hr_timesheet_id', 'title' => 'OT ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'timesheetable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'timesheetable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'owner_id', 'title' => 'Full Name', 'value_as_user_id' => true, 'cloneable' => !true],

            ['dataIndex' => 'ts_date', 'cloneable' => true],
            ['dataIndex' => 'project_id', 'cloneable' => true],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true],
            ['dataIndex' => 'lod_id', 'cloneable' => true],
            ['dataIndex' => 'discipline_id', 'cloneable' => true],
            ['dataIndex' => 'task_id', 'cloneable' => true],
            ['dataIndex' => 'sub_task_id', 'cloneable' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true, 'no_print' => true],
            ['dataIndex' => 'ts_hour', 'cloneable' => true],
            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }

    public function getManyLineParams_Workers()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            // ['dataIndex' => 'hr_timesheet_id', 'title' => 'OT ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'timesheetable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'timesheetable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'owner_id', 'title' => 'Full Name', 'value_as_user_id' => true, 'cloneable' => !true],

            ['dataIndex' => 'ts_date', 'cloneable' => true],
            ['dataIndex' => 'project_id', 'cloneable' => true],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true],
            ['dataIndex' => 'prod_routing_id', 'cloneable' => true],
            ['dataIndex' => 'lod_id', 'cloneable' => true],
            ['dataIndex' => 'discipline_id', 'cloneable' => true],
            ['dataIndex' => 'task_id', 'cloneable' => true],
            ['dataIndex' => 'sub_task_id', 'cloneable' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true, 'no_print' => true],
            ['dataIndex' => 'ts_hour', 'cloneable' => true],
            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
