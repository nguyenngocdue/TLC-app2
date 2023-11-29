<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_overtime_request_line extends ModelExtended
{
    protected $fillable = [
        "id", "hr_overtime_request_id", "user_id", "employeeid", "position_rendered",
        "ot_date", "from_time", "to_time", "break_time", "order_no", "owner_id",
        "total_time", "sub_project_id", "work_mode_id", "remark", "status",
        "month_remaining_hours", "month_allowed_hours",
        "year_remaining_hours", "year_allowed_hours",
        "position_id",
    ];
    public static $nameless = true;

    public static $eloquentParams = [
        "getHrOtr" => ['belongsTo', Hr_overtime_request::class, 'hr_overtime_request_id'],
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
        "getPosition" => ['belongsTo', User_position::class, 'position_id'],
    ];

    public function getHrOtr()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUser()
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

    public function getPosition()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ["dataIndex" => 'id', 'title' => 'OT Line ID', 'no_print' => true, 'invisible' => true],
            ['dataIndex' => 'hr_overtime_request_id', 'title' => 'OT ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'user_id', 'title' => 'Full Name', 'value_as_user_id' => true, 'cloneable' => !true, 'deaf' => true],
            ['dataIndex' => 'employeeid'],
            // ['dataIndex' => 'position_rendered', 'title' => 'Position'],
            ['dataIndex' => 'position_id', 'deaf' => true,],
            ['dataIndex' => 'ot_date', 'cloneable' => true],
            ['dataIndex' => 'month_allowed_hours', 'cloneable' => !true, 'invisible' => !true],
            ['dataIndex' => 'year_allowed_hours', 'cloneable' => !true, 'invisible' => !true],
            ['dataIndex' => 'from_time', 'cloneable' => true],
            ['dataIndex' => 'to_time', 'cloneable' => true],
            ['dataIndex' => 'break_time', 'cloneable' => true],
            ['dataIndex' => 'total_time', 'footer' => "agg_sum"],
            ['dataIndex' => 'month_remaining_hours', 'cloneable' => !true],
            ['dataIndex' => 'year_remaining_hours', 'cloneable' => !true],
            ['dataIndex' => 'sub_project_id', 'cloneable' => true, 'deaf' => true],
            ['dataIndex' => 'work_mode_id', 'cloneable' => true, 'no_print' => true, 'deaf' => true],
            ['dataIndex' => 'remark', 'cloneable' => true],
            ['dataIndex' => 'status', 'cloneable' => true, 'no_print' => true, 'invisible' => true],
        ];
    }
}
