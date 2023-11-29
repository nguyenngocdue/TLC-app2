<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Erp_routing_link extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "prod_discipline_id", "owner_id"];
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getProdRoutingDetails" => ["hasMany", Prod_routing_detail::class, "erp_routing_link_id"],
    ];

    public function getProdRoutingDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
