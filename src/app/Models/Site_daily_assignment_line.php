<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Site_daily_assignment_line extends ModelExtended
{
    protected $fillable = [
        "id",  "owner_id", "status", "order_no",
        "site_daily_assignment_id",
        "user_id", "employeeid", "sub_project_id",
    ];

    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Site_daily_assignment::class, 'site_daily_assignment_id'],
        "getUser" => ["belongsTo", User::class, 'user_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
    ];

    public static $oracyParams = [
        "getSiteTasks()" => ["getCheckedByField", Prod_routing_link::class],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSiteTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'site_daily_assignment_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => "user_id", 'value_as_user_id' => true,  'deaf' => true],
            ['dataIndex' => "employeeid",],
            ['dataIndex' => "sub_project_id", "cloneable" => true,],
            ['dataIndex' => "getSiteTasks()"],
        ];
    }
}
