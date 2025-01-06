<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing_detail extends ModelExtended
{
    protected $fillable = [
        "id",
        "prod_routing_id",
        "prod_routing_link_id",
        "erp_routing_link_id",
        "wir_description_id",
        "owner_id",
        "priority",
        "order_no",
        "target_hours",
        "target_man_power",
        "target_man_hours",
        "target_min_uom",
        "avg_actual_hours",
        "actual_hours_weight",
    ];
    public static $nameless = true;
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdRouting" => ['belongsTo', Prod_routing::class, 'prod_routing_id'],
        "getProdRoutingLink" => ['belongsTo', Prod_routing_link::class, 'prod_routing_link_id'],
        "getErpRoutingLink" => ['belongsTo', Erp_routing_link::class, 'erp_routing_link_id'],
        "getWirDescription" => ['belongsTo', Wir_description::class, 'wir_description_id'],
        "getProdSequences" => ['hasMany', Prod_sequence::class, 'prod_routing_link_id', 'prod_routing_link_id'],
    ];

    public function getProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getErpRoutingLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescription()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdSequences()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'prod_routing_id', 'invisible' => true, 'value_as_parent_id' => true],

            ['dataIndex' => 'prod_routing_link_id',],
            // ['dataIndex' => 'erp_routing_link_id',],
            ["dataIndex" => 'target_hours'],
            ["dataIndex" => 'target_man_power', 'cloneable' => true],
            ["dataIndex" => 'target_man_hours'],
            ["dataIndex" => 'target_min_uom'],
            ["dataIndex" => 'avg_actual_hours'],
            ["dataIndex" => 'actual_hours_weight'],

            ['dataIndex' => 'wir_description_id',],
        ];
    }
}
