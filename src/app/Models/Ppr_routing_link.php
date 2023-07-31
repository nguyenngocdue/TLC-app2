<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Ppr_routing_link extends ModelExtended
{
    protected $fillable = ["id", "name", "parent", "description", "slug", 'owner_id'];

    protected $table = 'ppr_routing_links';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getPprRoutingLines" => ['hasMany', Ppr_routing_line::class, 'ppr_routing_link_id'],
    ];
    public function getPprRoutingLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
