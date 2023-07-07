<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_induction extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "facilitator_id", "training_location",
        "owner_id", 'training_datetime', 'slug',
    ];
    protected $table = "hse_inductions";

    public static $eloquentParams = [
        "getLines" => ["hasMany", Hse_induction_line::class, "hse_induction_id"],
        'getFacilitator' => ['belongsTo', User::class, 'facilitator_id'],
    ];

    public static $oracyParams = [];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getFacilitator()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
