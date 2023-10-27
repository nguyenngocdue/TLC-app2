<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_page extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
        "kanban_bucket_id", "default_statuses",
    ];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_bucket::class, "kanban_bucket_id"],
        "getClusters" => ["hasMany", Kanban_task_cluster::class, "kanban_page_id"],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getClusters()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->orderBy('order_no');
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
