<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_routing extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "owner_id"];

    protected $table = 'prod_routings';
    protected static $statusless = true;

    public $eloquentParams = [
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getProdRoutingDetails" => ['hasMany', Prod_routing_detail::class, 'prod_routing_id'],

        "getProdSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],

        "getProdRoutingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'prod_routing_id', 'prod_routing_link_id'],
    ];

    public $oracyParams = [
        "getWirDescriptions()" => ["getCheckedByField", Wir_description::class],
        "getChklstTmpls()" => ["getCheckedByField", Qaqc_insp_tmpl::class],
        "getSubProjects()" => ["getCheckedByField", Sub_project::class],
    ];

    public function getProdRoutingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getProdOrders()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProdRoutingDetails()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProdSequences()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescriptions()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getChklstTmpls()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSubProjects()
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
