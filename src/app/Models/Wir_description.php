<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Wir_description extends ModelExtended
{
    protected $fillable = ["name", "description", "slug", "prod_discipline_id", "def_assignee", "owner_id"];
    protected $table = "wir_descriptions";

    public $eloquentParams = [
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getProdRoutingDetails" => ["hasMany", Prod_routing_detail::class, "wir_description_id"],
    ];

    public $oracyParams = [
        "getDefMonitors1()" => ["getCheckedByField", User::class],
        "getRoutings()" => ["getCheckedByField", Prod_routing::class],
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

    public function getRoutings()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
