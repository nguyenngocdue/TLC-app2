<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_place extends ModelExtended
{
    protected $fillable = ["name", "description", "status", "owner_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getWorkplaces" => ["hasMany", Workplace::class, 'travel_place_id'],
    ];

    public function getWorkplaces()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
