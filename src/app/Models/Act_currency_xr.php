<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Support\DateTimeConcern;

class Act_currency_xr extends ModelExtended
{
    protected $fillable = ["name", "description", "status", "month", "owner_id"];
    protected $table = "act_currency_xrs";
    public static $eloquentParams = [
        'getLines' => ['hasMany', Act_currency_xr_line::class, 'currency_xr_id'],
    ];
    public static $nameless = true;
    public function getName()
    {
        return DateTimeConcern::convertForLoading("picker_month", $this->month);
    }

    public static $oracyParams = [];
    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
