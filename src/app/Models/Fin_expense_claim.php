<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "owner_id",

        "rate_exchange_month_id",
        'counter_currency_id',
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getClaimableLines" => ['morphMany', Fin_expense_claim_line::class, 'claimable', 'claimable_type', 'claimable_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],

        "getTravelLines" => ["hasMany", Fin_expense_claim_travel_detail::class, "diginet_business_trip_line_finger_print"],

        // "getTravelRequestLines" => [
        //     'belongsToMany',
        //     Diginet_business_trip_line::class,
        //     'fin_expense_claim_travel_details',
        //     'fin_expense_claim_id',
        //     'diginet_business_trip_line_finger_print',
        //     'id',
        //     'finger_print',
        // ],
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

    // public function getTravelRequestLines()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4], $p[5], $p[6]);
    // }
}
