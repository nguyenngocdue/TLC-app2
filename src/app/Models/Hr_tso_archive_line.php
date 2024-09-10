<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_tso_archive_line extends ModelExtended
{
    protected $fillable = [
        'tso_archive_id',
        'user_id',

        'user_discipline_id',
        'user_cat_id',
        'date_time',
        'min',
        'project_id',
        'sub_project_id',
        'lod_id',
        'task_id',
        'sub_task_id',
        'work_mode_id',
        'remark',

        'status',
        'owner_id',
    ];

    public static $eloquentParams = [
        "getUser" => ['belongsTo', User::class, 'user_id'],
        "getUserDiscipline" => ['belongsTo', User_discipline::class, 'user_discipline_id'],
        "getUserCat" => ['belongsTo', User_category::class, 'user_cat_id'],
        "getProject" => ['belongsTo', Project::class, 'project_id'],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
        "getLod" => ['belongsTo', Pj_task_phase::class, 'lod_id'],
        "getTask" => ['belongsTo', Hr_tso_archive_task::class, 'task_id'],
        "getSubTask" => ['belongsTo', Hr_tso_archive_sub_task::class, 'sub_task_id'],
        "getWorkMode" => ['belongsTo', Work_mode::class, 'work_mode_id'],
    ];

    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

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

    public function getLod()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWorkMode()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
