<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_place_pair extends ModelExtended
{
    protected $fillable = [
        "name", "description",
        "from_place_id", "to_place_id", "value",
        "status", "owner_id"
    ];
    protected $with = ["getFromPlace", "getToPlace"];
    public static $statusless = true;
    // public static $nameless = true;
    public function getNameAttribute($value)
    {
        $from_place = $this->getFromPlace;
        $to_place = $this->getToPlace;
        return ($from_place->name ?? "") . " -> " . ($to_place->name ?? "");
    }

    public static $eloquentParams = [
        "getFromPlace" => ["belongsTo", Act_travel_place::class, 'from_place_id'],
        "getToPlace" => ["belongsTo", Act_travel_place::class, 'to_place_id'],
    ];

    public function getFromPlace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getToPlace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
