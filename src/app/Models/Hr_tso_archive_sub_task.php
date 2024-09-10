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
        "getTasks" => ["belongsToMany", Hr_tso_archive_task::class, "ym2m_hr_tso_archive_sub_task_hr_tso_archive_task"],
    ];

    public function getTasks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
