<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_routing_line extends ModelExtended
{
    protected $fillable = [
        "id", "ppr_routing_id", "ppr_routing_link_id", 
        "target_hours", "target_man_hours",  "owner_id",
        "order_no",
    ];
    protected $table = "ppr_routing_lines";
    public static $nameless = true;
    protected static $statusless = true;

    public static $eloquentParams = [
        "getPprRouting" => ['belongsTo', Ppr_routing::class, 'ppr_routing_id'],
        "getPprRoutingLink" => ['belongsTo', Ppr_routing_link::class, 'ppr_routing_link_id'],
    ];

    public function getPprRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPprRoutingLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'ppr_routing_id', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'ppr_routing_link_id',],
            ["dataIndex" => 'target_hours'],
            ["dataIndex" => 'target_man_hours'],
        ];
    }
}
