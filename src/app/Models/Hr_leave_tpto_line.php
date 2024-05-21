<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_leave_tpto_line extends ModelExtended
{
    protected $fillable = [
        "id", "owner_id", "workplace_id", "user_id", "remark",  "order_no",
        "annual_leave", "sick_leave", "domestic_violence_leave", "cash_out",
        "remark", "year", "parent_id", "starting_date",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
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

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'year', 'cloneable' => true,],
            ['dataIndex' => 'user_id', "title" => "Employee"],
            ['dataIndex' => 'workplace_id'],
            ['dataIndex' => 'starting_date'],

            ['dataIndex' => "annual_leave", 'footer' => 'agg_sum'],
            ['dataIndex' => "sick_leave", 'footer' => 'agg_sum'],
            ['dataIndex' => "domestic_violence_leave", 'footer' => 'agg_sum'],
            ['dataIndex' => "cash_out", 'footer' => 'agg_sum'],
            ['dataIndex' => "remark"],
        ];
    }
}
