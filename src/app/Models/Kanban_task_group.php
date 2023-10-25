<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_group extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id', "kanban_cluster_id",
        "time_counting_type", "assignee_1", "previous_group_id", "rectified_group_id",
    ];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_cluster::class, "kanban_cluster_id"],
        "getTasks" => ["hasMany", Kanban_task::class, "kanban_group_id"],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
    ];

    public static $oracyParams = [];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
