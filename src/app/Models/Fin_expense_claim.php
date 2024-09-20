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
        "employee_id",
        "user_discipline_id",
        "receiving_method_id",

        "travel_from_place_id",
        "travel_to_place_id",
        "travel_place_pair_id",
        "travel_allowance_per_day",

        "travel_from_date",
        "travel_to_date",
        "travel_day_count",
        "hr_adjusted_date_count_0",
        "hr_adjusted_date_count_1",

        "rate_exchange_month_id",
        'counter_currency_id',
        "travel_currency_id",
        "currency_pair_id",
        "travel_rate_exchange",
        "travel_allowance_amount",

        "advance_total",
        "travel_allowance_total",
        "expense_total",
        "total_reimbursed",
        "total_reimbursed_in_words_0",
        "total_reimbursed_in_words_1",
    ];

    // public static $statusless = true;

    public static $eloquentParams = [
        "getClaimableLines" => ['morphMany', Fin_expense_claim_line::class, 'claimable', 'claimable_type', 'claimable_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],
        'getReceivingMethod' => ['belongsTo', Term::class, 'receiving_method_id'],

        "getAdvanceLines" => ["hasMany", Fin_expense_claim_adv_detail::class, "fin_expense_claim_id"],
        "getTravelLines" => ["hasMany", Fin_expense_claim_travel_detail::class, "fin_expense_claim_id"],

        "getTravelCurrency" => ['belongsTo', Act_currency::class, 'travel_currency_id'],
        "getCurrencyPair" => ['belongsTo', Act_currency_pair::class, 'currency_pair_id'],

        "getRequester" => ['belongsTo', User::class, 'user_id'],
        "getUserDiscipline" => ['belongsTo', User_discipline::class, 'user_discipline_id'],
        "getTravelFromPlace" => ['belongsTo', Act_travel_place::class, 'travel_from_place_id'],
        "getTravelToPlace" => ['belongsTo', Act_travel_place::class, 'travel_to_place_id'],
        "getTravelPlacePair" => ['belongsTo', Act_travel_place_pair::class, 'travel_place_pair_id'],

    ];

    public function getClaimableLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getReceivingMethod()
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

    public function getAdvanceLines()
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

    public function getUserDiscipline()
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
