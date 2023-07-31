<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_routing extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "owner_id"];

    protected $table = 'ppr_routings';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getPprRoutingDetails" => ['hasMany', Ppr_routing_detail::class, 'ppr_routing_id'],
        "getPprRoutingLinks" => ['belongsToMany', Ppr_routing_link::class, 'ppr_routing_details', 'ppr_routing_id', 'ppr_routing_link_id'],
    ];

    public static $oracyParams = [
    ];

    public function getPprRoutingLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getPprOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getPprRoutingDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
        ];
    }
}
