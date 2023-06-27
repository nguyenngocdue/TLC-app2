<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Prod_discipline extends ModelExtended
{
    public $fillable = ["id", "name", "description", "slug", "def_assignee", "owner_id"];

    protected $table = 'prod_disciplines';
    protected static $statusless = true;

    public static $eloquentParams = [
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getProdRoutingLink" => ['hasMany', Prod_routing_link::class, 'prod_discipline_id'],
        "getDiscipline1s" => ['hasMany', Prod_discipline_1::class, 'prod_discipline_id'],
        "getErpRoutingLinks" => ['hasMany', Erp_routing_link::class, 'prod_discipline_id'],
        "getWirDescriptions" => ['hasMany', Wir_description::class, 'prod_discipline_id'],
    ];

    public static $oracyParams = [
        "getDefMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getProdRoutingLink()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline1s()
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
    public function getErpRoutingLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWirDescriptions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
