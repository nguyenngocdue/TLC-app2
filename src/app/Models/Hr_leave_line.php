<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_line extends ModelExtended
{
    protected $fillable = [
        "id",
        "leaveable_type",
        "leaveable_id",
        "owner_id",
        "leave_date",
        "leave_type_id",
        "leave_days",
        "leave_cat_id",
        "workplace_id",
        "user_id",
        "remark",
        "order_no",
        "remaining_days",
        "allowed_days"
        // "status",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getLeaveType" => ['belongsTo', Hr_leave_type::class, 'leave_type_id'],
        "getLeaveCat" => ['belongsTo', Hr_leave_cat::class, 'leave_cat_id'],
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

    public function getLeaveCat()
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

            ['dataIndex' => 'user_id', 'title' => 'Full Name', /*'value_as_user_id' => true,*/ 'cloneable' => !true],
            ['dataIndex' => 'workplace_id'],

            ['dataIndex' => 'leave_date', 'cloneable' => true],

            ['dataIndex' => 'leave_cat_id'],
            ['dataIndex' => 'leave_type_id'],
            // ['dataIndex' => 'allowed_days',],
            ['dataIndex' => 'leave_days', 'footer' => 'agg_sum'],
            // ['dataIndex' => 'remaining_days',],

            ['dataIndex' => 'leaveable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'leaveable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'remark', 'cloneable' => true],

        ];
    }
}
