<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
        "kanban_group_id", 'assignee_1', "kanban_task_transition_id", "target_hours",
    ];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_group::class, "kanban_group_id"],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "getTransitions" => ["hasMany", Kanban_task_transition::class, "kanban_task_id"],
    ];

    public static $oracyParams = [
        // "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTransitions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    // public function getMonitors1()
    // {
    //     $p = static::$oracyParams[__FUNCTION__ . '()'];
    //     return $this->{$p[0]}(__FUNCTION__, $p[1]);
    // }
}
