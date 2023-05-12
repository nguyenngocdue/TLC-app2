<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Erp_routing_link extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "prod_discipline_id", "owner_id"];
    protected $table = "erp_routing_links";

    public $eloquentParams = [
        "getProdRoutingDetails" => ["hasMany", Prod_routing_detail::class, "erp_routing_link_id"],
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],
    ];

    public function getProdRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
