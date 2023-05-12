<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing extends ModelExtended
{
    public $timestamps = true;
    protected $fillable = ["name", "description", "slug", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'prod_routings';

    public $eloquentParams = [
        "prodRoutingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'prod_routing_id', 'prod_routing_link_id'],
        "prodOrders" => ['hasMany', Prod_order::class],
        "prodSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],
        "prodRoutingDetails" => ['hasMany', Prod_routing_detail::class, 'prod_routing_id'],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],
    ];

    public $oracyParams = [
        "getWirDescriptions()" => ["getCheckedByField", Wir_description::class],
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

    public function prodRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function prodSequences()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescriptions()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
        ];
    }
}
