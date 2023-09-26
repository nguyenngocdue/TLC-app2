<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_group extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id'];

    public static $eloquentParams = [
        "getTasks" => ["HasMany", Kanban_task::class, "kanban_group_id"],
    ];

    public static $oracyParams = [];

    public function getTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
