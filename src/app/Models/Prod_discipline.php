<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_disciplines';
    public $timestamps = true;

    public $eloquentParams = [
        "routingLink" => ['hasMany', Prod_routing_link::class, 'prod_discipline_id'],
    ];

    public function routingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
