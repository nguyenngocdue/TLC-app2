<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task extends ModelExtended
{
    protected $fillable = [
        'id', 'name', 'description', 'status', 'owner_id',
        "kanban_group_id", 'assignee_1', "kanban_task_transition_id",
        "target_hours", "task_priority",
    ];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_group::class, "kanban_group_id"],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        "getTransitions" => ["hasMany", Kanban_task_transition::class, "kanban_task_id"],
        "attachment_kanban_task" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getPriority" => ['belongsTo', Term::class, 'task_priority'],
    ];

    public static $oracyParams = [];

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

    public function attachment_kanban_task()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
