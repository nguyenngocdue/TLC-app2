<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing extends ModelExtended
{
    public $timestamps = true;
    protected $fillable = ["name", "description", "slug"];
    protected $primaryKey = 'id';
    protected $table = 'prod_routings';

    public $eloquentParams = [
        "prodRoutingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'prod_routing_id', 'prod_routing_link_id'],
        "prodOrders" => ['hasMany', Prod_order::class],
        "prodRuns" => ["hasManyThrough", Prod_run::class, Prod_order::class],
    ];

    public function prodRoutingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function prodOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function prodRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id', 'renderer' => 'id', 'align' => 'center', 'type' => 'prod_routings'],
            ['dataIndex' => 'name'],
        ];
    }
}
