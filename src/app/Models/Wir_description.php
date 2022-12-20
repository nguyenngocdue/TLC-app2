<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Wir_description extends ModelExtended
{
    protected $fillable = ["name", 'description', 'slug'];
    protected $table = "wir_descriptions";

    public $eloquentParams = [
        "prodRoutingDetails" => ["hasMany", Prod_routing_detail::class, "wir_description_id",]
    ];

    public function prodRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
