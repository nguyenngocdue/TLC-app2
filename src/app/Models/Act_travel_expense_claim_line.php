<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_expense_claim_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "unit_price",
        "quantity", "currency_id", "travel_expense_claim_id", "total_amount", "currency_pair_id",
        "rate_exchange", "total_estimated_amount", "order_no", "owner_id"
    ];
    protected $table = "act_travel_expense_claim_lines";

    public static $eloquentParams = [
        'getCurrency' => ['belongsTo', Act_currency::class, 'currency_id'],
        'getCurrencyPair' => ['belongsTo', Act_currency_pair::class, 'currency_pair_id'],
        'getParent' => ['belongsTo', Act_travel_expense_claim::class, 'travel_expense_claim_id'],
    ];

    public static $oracyParams = [];
    public function getCurrency()
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
            ['dataIndex' => 'unit_price', 'cloneable' => true],
            ['dataIndex' => 'quantity', 'cloneable' => true, 'footer' => 'agg_sum'],
            ['dataIndex' => 'currency_id', 'value_as_parent_id' => true,],
            ['dataIndex' => 'total_amount',],
            ['dataIndex' => 'currency_pair_id', 'value_as_parent_id' => true,],
            ['dataIndex' => 'rate_exchange',],
            ['dataIndex' => 'total_estimated_amount', 'footer' => 'agg_sum'],
        ];
    }
}
