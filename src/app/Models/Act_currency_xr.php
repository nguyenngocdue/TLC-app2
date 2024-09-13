<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_currency_xr extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "status",
        "month",
        "owner_id",
    ];
    public static $eloquentParams = [
        'getLines' => ['hasMany', Act_currency_xr_line::class, 'currency_xr_id'],
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        return substr($this->month, 0, 7);
        // return DateTimeConcern::convertForLoading("picker_month", $this->month);
    }

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
