<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hr_tso_archive_line extends ModelExtended
{
    protected $fillable = [
        'tso_archive_id',
        'user_id',

        'user_discipline_id',
        'user_cat_id',
        'date_time',
        'min',
        'project_id',
        'sub_project_id',
        'lod_id',
        'task_id',
        'sub_task_id',
        'work_mode_id',
        'remark',

        'status',
        'owner_id',
    ];
    // public static $nameless = true;
    // public static $statusless = true;

    public static $eloquentParams = [
        // "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    // public function getWorkplace()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
}
