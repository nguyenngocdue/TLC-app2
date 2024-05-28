<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Pj_task_phase extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'owner_id'];

    public static $statusless = true;

    public static $eloquentParams = [];

    public static $oracyParams = [
        // "getDisciplinesOfTask()" => ["getCheckedByField", User_discipline::class],
    ];

    //     public function getDisciplinesOfTask()
    //     {
    //         $p = static::$oracyParams[__FUNCTION__ . '()'];
    //         return $this->{$p[0]}(__FUNCTION__, $p[1]);
    //     }
}
