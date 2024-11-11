<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Mec_book extends ModelExtended
{
    public static $hasDueDate = true;

    protected $fillable = [
        "id",
        "name",
        "description",
        "doc_id",

        "priority_id",
        "project_id",
        "sub_project_id",

        "assignee_1",
        "assignee_2",
        "assignee_3",

        "due_date",
        "closed_at",
        "status",
        "order_no",
        "owner_id",
    ];

    public static $eloquentParams = [
        'getProject' => ['belongsTo', Project::class, 'project_id'],
        'getSubProject' => ['belongsTo', Sub_project::class, 'sub_project_id'],
        'getPriority' => ['belongsTo', Priority::class, 'priority_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getAssignee2' => ['belongsTo', User::class, 'assignee_2'],
        'getAssignee3' => ['belongsTo', User::class, 'assignee_3'],

        "attachment_mec_book" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "getMonitors1" => ["belongsToMany", User::class, 'ym2m_mec_book_user_monitor_1'],
    ];

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

    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_mec_book()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
