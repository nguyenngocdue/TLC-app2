<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_sub_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    public static $statusless = true;

    public static $eloquentParams = [
        "getParentTasks" => ["belongsToMany", Pj_task::class, "ym2m_pj_sub_task_pj_task"],
    ];

    public function getParentTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
