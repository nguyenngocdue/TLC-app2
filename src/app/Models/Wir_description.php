<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Wir_description extends ModelExtended
{
    protected $fillable = [
        "name",
        "description",
        "slug",
        "prod_discipline_id",
        "def_assignee",
        "owner_id",
        "wir_weight",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
        "getDefAssignee" => ['belongsTo', User::class, 'def_assignee'],

        "getProdRoutingDetails" => ["hasMany", Prod_routing_detail::class, "wir_description_id"],
        //Many to many
        "getDefMonitors1" => ["belongsToMany", User::class, "ym2m_user_wir_description_def_monitor_1"],
        "getProdRoutingsOfWirDescription" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_wir_description"],
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
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingsOfWirDescription()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
