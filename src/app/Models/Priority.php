<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Priority extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "duration", "field_id", "owner_id"];

    public static $statusless = true;

    public static $eloquentParams = [
        "getField" => ['belongsTo', Field::class, 'field_id'],
    ];

    public function getField()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
