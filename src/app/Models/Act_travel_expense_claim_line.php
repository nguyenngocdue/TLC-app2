<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_expense_claim_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "unit_price",
        "quantity", "currency_id", "travel_expense_claim_id", "total_amount", "currency_pair_id",
        "rate_exchange", "total_estimated_amount", "order_no", "owner_id",
        "counter_currency_id", "rate_exchange_month_id",
    ];

    public static $eloquentParams = [
        'getCurrency' => ['belongsTo', Act_currency::class, 'currency_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        'getCurrencyPair' => ['belongsTo', Act_currency_pair::class, 'currency_pair_id'],
        'getParent' => ['belongsTo', Act_travel_expense_claim::class, 'travel_expense_claim_id'],
    ];

    public function getCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCounterCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRateExchangeMonth()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrencyPair()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'travel_expense_claim_id', 'value_as_parent_id' => true, 'invisible' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'description',],
            ['dataIndex' => 'quantity', 'cloneable' => true, 'footer' => 'agg_sum'],
            ['dataIndex' => 'unit_price', 'cloneable' => true],
            ['dataIndex' => 'currency_id',],
            ['dataIndex' => 'total_amount',],
            ['dataIndex' => 'rate_exchange_month_id', 'invisible' => true],
            ['dataIndex' => 'counter_currency_id', 'invisible' => true],
            ['dataIndex' => 'currency_pair_id', 'invisible' => true],
            ['dataIndex' => 'rate_exchange',],
            ['dataIndex' => 'total_estimated_amount', 'footer' => 'agg_sum'],
        ];
    }
}
