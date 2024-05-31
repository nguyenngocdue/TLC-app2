<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_currency_xr_line extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "status", "month", "currency_xr_id", "currency_pair_id", "value", "order_no", "owner_id"];

    public static $eloquentParams = [
        'getCurrencyPair' => ['belongsTo', Act_currency_pair::class, 'currency_pair_id'],
        'getCurrencyXr' => ['belongsTo', Act_currency_xr::class, 'currency_xr_id'],
    ];

    public function getCurrencyPair()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrencyXr()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'currency_xr_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'month', 'cloneable' => true],
            ['dataIndex' => 'currency_pair_id', 'cloneable' => true],
            ['dataIndex' => 'value'],
        ];
    }
}
