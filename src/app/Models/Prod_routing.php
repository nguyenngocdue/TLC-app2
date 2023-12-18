<?php

namespace App\Models;

use App\BigThink\HasShowOnScreens;
use App\BigThink\ModelExtended;

class Prod_routing extends ModelExtended
{
    use HasShowOnScreens;

    protected $fillable = [
        "name", "description", "slug", "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getProdRoutingDetails" => ['hasMany', Prod_routing_detail::class, 'prod_routing_id'],

        "getProdSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],

        "getProdRoutingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'prod_routing_id', 'prod_routing_link_id'],
    ];

    public static $oracyParams = [
        "getWirDescriptions()" => ["getCheckedByField", Wir_description::class],
        "getChklstTmpls()" => ["getCheckedByField", Qaqc_insp_tmpl::class],
        "getSubProjects()" => ["getCheckedByField", Sub_project::class],
        "getScreensShowMeOn()" => ["getCheckedByField", Term::class],
        "getExternalInspectorsOfProdRouting()" => ['getCheckedByField', User::class],
    ];

    public function getProdRoutingLinks()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('target_man_power', 'target_hours', 'target_man_hours', 'target_min_uom');
    }

    public function getProdOrders()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProdRoutingDetails()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1]);
    }

    public function getProdSequences()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescriptions()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getChklstTmpls()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSubProjects()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getExternalInspectorsOfProdRouting()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getScreensShowMeOn()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
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
