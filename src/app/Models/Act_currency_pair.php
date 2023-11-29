<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_currency_pair extends ModelExtended
{
    protected $fillable = ["name", "description", "status", "base_currency_id", "counter_currency_id", "owner_id"];
    protected $with = ['getBaseCurrency', 'getCountCurrency'];

    public static $eloquentParams = [
        'getBaseCurrency' => ['belongsTo', Act_currency::class, 'base_currency_id'],
        'getCountCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $base = $this->getBaseCurrency;
        $counter = $this->getCountCurrency;
        return ($base->name ?? "") . "/" . ($counter->name ?? "");
    }

    public static $oracyParams = [];
    public function getBaseCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCountCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
