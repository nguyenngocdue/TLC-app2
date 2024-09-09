<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_tso_archive_sub_task extends ModelExtended
{
    protected $fillable = [
        'id',
        'name',
        'description',

        'task_id',
        'status',
        'owner_id',
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getTask" => ['belongsTo', Hr_tso_archive_task::class, 'task_id'],
    ];

    public function getTask()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
