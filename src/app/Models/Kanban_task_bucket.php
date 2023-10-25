<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Kanban_task_bucket extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'status', 'owner_id', "kanban_group_id"];

    public static $eloquentParams = [

        "getPages" => ["hasMany", Kanban_task_page::class, "kanban_bucket_id"],
    ];

    public static $oracyParams = [];

    public function getPages()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2])->orderBy('order_no');
    }
}
