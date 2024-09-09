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
