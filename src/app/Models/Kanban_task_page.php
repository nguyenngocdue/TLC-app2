<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_page extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id', "kanban_page_id",];

    public static $eloquentParams = [
        "getClusters" => ["hasMany", Kanban_task_cluster::class, "kanban_page_id"],
    ];

    public static $oracyParams = [];

    public function getClusters()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
