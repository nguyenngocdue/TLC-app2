<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_timesheet_officer_line extends ModelExtended
{
    protected $fillable = [
        'hr_timesheet_officer_id',
        'start_time',
        'duration_in_min',
        'duration_in_hour',
        'ts_date',
        'ts_hour',
        'project_id',
        'sub_project_id',
        'discipline_id',
        'task_id',
        'sub_task_id',
        'work_mode_id',
        'owner_id',
        'order_no',
        'id',
        'remark',
        'status',
        'lod_id',
        'prod_routing_id',
        'user_id'
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getTask" => ['belongsTo', Pj_task::class, "task_id"],
        "getSubTask" => ['belongsTo', Pj_sub_task::class, "sub_task_id"],
        "getDiscipline" => ['belongsTo', User_discipline::class, "discipline_id"],
        "getLod" => ['belongsTo', Pj_task_phase::class, "lod_id"],

        "getParent" => ['belongsTo', Hr_timesheet_officer::class, 'hr_timesheet_officer_id'],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLod()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkMode()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],

            ['dataIndex' => 'user_id', 'title' => 'Full Name', 'invisible' => true, 'value_as_user_id' => true, 'cloneable' => !true],

            // ['dataIndex' => 'ts_date', 'cloneable' => true],
            ['dataIndex' => 'start_time', 'cloneable' => true],
            ['dataIndex' => 'duration_in_min', 'cloneable' => true],
            // ['dataIndex' => 'project_id', 'cloneable' => true],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true],
            ['dataIndex' => 'lod_id', 'title' => 'Phase', 'cloneable' => true],
            ['dataIndex' => 'discipline_id', 'invisible' => true, 'cloneable' => true],
            ['dataIndex' => 'task_id', 'cloneable' => true],
            ['dataIndex' => 'sub_task_id', 'cloneable' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true, 'no_print' => true],
            // ['dataIndex' => 'ts_hour', 'cloneable' => true],
            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
