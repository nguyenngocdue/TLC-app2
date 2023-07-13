<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_expense_claimable extends ModelExtended
{
    protected $fillable = ["from_id", "to_id", "price","owner_id"];
    protected $table = "act_travel_expense_claimables";
    public static $eloquentParams = [
        'getFrom' => ['belongsTo', Act_travel_place::class, 'from_id'],
        'getTo' => ['belongsTo', Act_travel_place::class, 'to_id'],
    ];

    public static $oracyParams = [];
    public function getFrom()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
