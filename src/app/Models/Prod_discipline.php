<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug", "def_assignee", "owner_id"];
    protected $primaryKey = 'id';
    protected $table = 'prod_disciplines';

    public $eloquentParams = [
        "routingLink" => ['hasMany', Prod_routing_link::class, 'prod_discipline_id'],
        "getDiscipline1s" => ['hasMany', Prod_discipline_1::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],
        "getErpRoutingLinks" => ['hasMany', Erp_routing_link::class, 'prod_discipline_id'],
        "getWirDescriptions" => ['hasMany', Wir_description::class, 'prod_discipline_id'],
        "getOwner" => ['belongsTo', User::class, 'owner_id'],
    ];

    public $oracyParams = [
        "getDefMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function routingLink()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline1s()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefAssignee()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDefMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getErpRoutingLinks()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWirDescriptions()
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
