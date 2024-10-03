<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "short_name",
        "owner_id",
        "has_punchlist",
        "hide_ncr_count",
        "order_no",
        "parent_id",
    ];

    public static $statusless = true;

    public static $eloquentParams = [
        "getSheets" => ["hasMany", Qaqc_insp_tmpl_sht::class, "qaqc_insp_tmpl_id"],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'qaqc_insp_tmpl_id'],

        "getProdRoutingsOfInspTmpl" => ["belongsToMany", Prod_routing::class, "ym2m_prod_routing_qaqc_insp_tmpl"],
        "getExternalInspectorsOfQaqcInspTmpl" => ['belongsToMany', User::class, "ym2m_qaqc_insp_tmpl_user_ext_insp"],
        "getCouncilMembersOfQaqcInspTmpl" => ['belongsToMany', User::class, "ym2m_qaqc_insp_tmpl_user_council_member"],
    ];

    public function getSheets()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspChklsts()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRoutingsOfInspTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExternalInspectorsOfQaqcInspTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCouncilMembersOfQaqcInspTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => !true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'parent_id', 'value_as_parent_id' => true, 'invisible' => true,],
            ['dataIndex' => 'name',],
        ];
    }
}
