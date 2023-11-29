<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Work_area extends ModelExtended
{
    protected $fillable = ['id', 'name', 'description', 'slug', 'workplace_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace_id'],
    ];

    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
