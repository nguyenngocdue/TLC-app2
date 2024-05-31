<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_cluster extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id', 'kanban_page_id'];

    public static $eloquentParams = [
        "getParent" => ["belongsTo", Kanban_task_page::class, "kanban_page_id"],
        "getGroups" => ["hasMany", Kanban_task_group::class, "kanban_cluster_id"],
    ];

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getGroups()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->orderBy('order_no');
    }
}
