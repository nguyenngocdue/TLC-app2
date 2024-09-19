<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "owner_id",
        "user_id",

        "travel_from_place_id",
        "travel_to_place_id",
        "travel_place_pair_id",
        "travel_allowance_per_day",

        "travel_from_date",
        "travel_to_date",
        "travel_day_count",
        "hr_adjusted_date_count_0",
        "hr_adjusted_date_count_1",
        "travel_allowance_total",

        "rate_exchange_month_id",
        'counter_currency_id',
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getClaimableLines" => ['morphMany', Fin_expense_claim_line::class, 'claimable', 'claimable_type', 'claimable_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],

        "getTravelLines" => ["hasMany", Fin_expense_claim_travel_detail::class, "fin_expense_claim_id"],
        "getRequester" => ['belongsTo', User::class, 'user_id'],
        "getTravelFromPlace" => ['belongsTo', Act_travel_place::class, 'travel_from_place_id'],
        "getTravelToPlace" => ['belongsTo', Act_travel_place::class, 'travel_to_place_id'],
        "getTravelPlacePair" => ['belongsTo', Act_travel_place_pair::class, 'travel_place_pair_id'],

    ];

    public function getClaimableLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRateExchangeMonth()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCounterCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTravelLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRequester()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTravelFromPlace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTravelToPlace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTravelPlacePair()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
