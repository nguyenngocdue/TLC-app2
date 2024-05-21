<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_line extends ModelExtended
{
    protected $fillable = [
        "id", "leaveable_type", "leaveable_id", "owner_id",
        "leave_date", "leave_type_id", "leave_days", "absence_type_id",
        "workplace_id", "user_id", "remark",  "order_no",
        // "status",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getLeaveType" => ['belongsTo', Term::class, 'leave_type_id'],
        "getAbsenceType" => ['belongsTo', Term::class, 'absence_type_id'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],

        "leaveable" => ['morphTo', Hr_leave_line::class, 'leaveable_type', 'leaveable_id'],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getLeaveType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAbsenceType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function leaveable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'timesheetable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'timesheetable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'user_id', 'title' => 'Full Name', 'value_as_user_id' => true, 'cloneable' => !true],

            // ['dataIndex' => 'ts_date', 'cloneable' => true],
            ['dataIndex' => 'start_time', 'cloneable' => true],
            ['dataIndex' => 'project_id', 'cloneable' => true],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true],
            ['dataIndex' => 'lod_id', 'cloneable' => true],
            ['dataIndex' => 'discipline_id', 'cloneable' => true],
            ['dataIndex' => 'task_id', 'cloneable' => true],
            ['dataIndex' => 'sub_task_id', 'cloneable' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true, 'no_print' => true],
            // ['dataIndex' => 'ts_hour', 'cloneable' => true],
            ['dataIndex' => 'duration_in_min', 'cloneable' => true],
            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
