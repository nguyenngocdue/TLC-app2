<?php

namespace App\Models;

use App\BigThink\HasShowOnScreens;
use App\BigThink\ModelExtended;

class Prod_routing extends ModelExtended
{
    use HasShowOnScreens;

    protected $fillable = [
        "name",
        "description",
        "slug",
        "owner_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getProdOrders" => ['hasMany', Prod_order::class],
        "getProdRoutingDetails" => ['hasMany', Prod_routing_detail::class, 'prod_routing_id'],
        "getProdSequences" => ["hasManyThrough", Prod_sequence::class, Prod_order::class],
        "getProdRoutingLinks" => ['belongsToMany', Prod_routing_link::class, 'prod_routing_details', 'prod_routing_id', 'prod_routing_link_id'],

        //Many to many
        "getWirDescriptions" => ["belongsToMany", Wir_description::class, "ym2m_prod_routing_wir_description"],
        "getChklstTmpls" => ["belongsToMany", Qaqc_insp_tmpl::class, "ym2m_prod_routing_qaqc_insp_tmpl"],
        "getSubProjects" => ["belongsToMany", Sub_project::class, "ym2m_prod_routing_sub_project"],
        "getScreensShowMeOn" => ["belongsToMany", Term::class, "ym2m_prod_routing_term_show_me_on"],
        "getExternalInspectorsOfProdRouting" => ["belongsToMany", User::class, "ym2m_prod_routing_user_ext_insp"],
        "getCouncilMembersOfProdRouting" => ["belongsToMany", User::class, "ym2m_prod_routing_user_council_member"],
        "getShippingAgentsOfProdRouting" => ["belongsToMany", User::class, "ym2m_prod_routing_user_shipping_agent"],
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
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getChklstTmpls()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSubProjects()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExternalInspectorsOfProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCouncilMembersOfProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getShippingAgentsOfProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getScreensShowMeOn()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
        ];
    }
}
