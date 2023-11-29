<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "owner_id", "slug",
        "consent_number",  "progress", "owner_id",
        "qaqc_insp_tmpl_id", "sub_project_id",
        "prod_order_id", "prod_routing_id",
        'status',
    ];

    public static $eloquentParams = [
        "getProdOrder" => ["belongsTo", Prod_order::class, "prod_order_id"],
        "getProdRouting" => ["belongsTo", Prod_routing::class, "prod_routing_id"],
        "getQaqcInspTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, "qaqc_insp_tmpl_id"],

        "getSheets" => ["hasMany", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, 'sub_project_id'],
    ];

    public function getProdOrder()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSheets()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $tmp = $this->getProdOrder->getSubProject;
        $relation = $tmp->belongsTo(Project::class, 'project_id');
        return $relation;
    }

    // public function getSubProject()
    // {
    //     $tmp = $this->getProdOrder;
    //     $relation = $tmp->belongsTo(Sub_project::class, 'sub_project_id');
    //     return $relation;
    // }

    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => "id"],
            ["dataIndex" => "name"],
            ["dataIndex" => "prod_order_id"],
            ["dataIndex" => "qaqc_insp_tmpl_id"],
            ["dataIndex" => "progress"],
            ["dataIndex" => "status"],
        ];
    }
}
