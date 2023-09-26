<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_cluster extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    public static $eloquentParams = [
        "getGroups" => ["hasMany", Kanban_task_group::class, "kanban_cluster_id"],
    ];

    public static $oracyParams = [];

    public function getGroups()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
