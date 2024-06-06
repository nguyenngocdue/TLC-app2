<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class User_discipline extends ModelExtended
{
    protected $fillable = [
        "name", "description", "def_assignee", "slug",
        "show_in_task_budget",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getUsers" => ['hasMany', User::class, 'discipline'],
        "getDefAssignee" => ["belongsTo", User::class, 'def_assignee'],

        //Many to many
        "getMonitors1" => ["belongsToMany", User::class, "ym2m_user_discipline_user_monitor_1"],
        "getTasksOfDiscipline" => ["belongsToMany", Pj_task::class, "ym2m_pj_task_user_discipline"],
    ];

    public function getTasksOfDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUsers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
