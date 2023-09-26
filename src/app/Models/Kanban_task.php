<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id', "kanban_group_id"];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_group::class, "kanban_group_id"],
    ];

    public static $oracyParams = [];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
