<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_page extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id', "kanban_bucket_id",];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_bucket::class, "kanban_bucket_id"],
        "getClusters" => ["hasMany", Kanban_task_cluster::class, "kanban_page_id"],
    ];

    public static $oracyParams = [];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getClusters()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
