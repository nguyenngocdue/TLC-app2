<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_wir_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "order_no", "owner_id",
        "prod_order_id", "qc_order",
        "qc_total", "qc_accepted", "qc_remaining", "qc_rejected", "qaqc_wir_id",
    ];
    public static $nameless = true;
    public static $eloquentParams = [
        "getProdOrder" => ['belongsTo', Prod_order::class, 'prod_order_id'],
        // 'getParent' => ['belongsTo', Qaqc_wir::class, 'qaqc_wir_id'],
    ];

    // public function getParent()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }

    public function getProdOrder()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'invisible' => true,],
            ['dataIndex' => 'qaqc_wir_id', 'title' => 'ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'prod_order_id',],
            ['dataIndex' => 'qc_order',],
            ['dataIndex' => 'qc_total',],
            ['dataIndex' => 'qc_accepted',],
            ['dataIndex' => 'qc_rejected',],
            ['dataIndex' => 'qc_remaining',],
            // ['dataIndex' => 'created_at',],
        ];
    }
}
