<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Wir_description extends ModelExtended
{
    protected $fillable = [
        "name", "description", "slug",
        "prod_discipline_id", "def_assignee", "owner_id",
        "wir_weight",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getProdRoutingDetails" => ["hasMany", Prod_routing_detail::class, "wir_description_id"],
    ];

    public static $oracyParams = [
        "getDefMonitors1()" => ["getCheckedByField", User::class],
        "getProdRoutingsOfWirDescription()" => ["getCheckedByField", Prod_routing::class],
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

    public function getDefAssignee()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDefMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getProdRoutingsOfWirDescription()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
