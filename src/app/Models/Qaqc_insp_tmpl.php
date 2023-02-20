<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl extends ModelExtended
{
    protected $fillable = ["id", "prod_routing_id", "name", "description", "slug", "owner_id"];
    protected $table = "qaqc_insp_tmpls";

    public $eloquentParams = [
        "getSheets" => ["hasMany", Qaqc_insp_tmpl_sht::class, "qaqc_insp_tmpl_id"],
        "getProdRouting" => ["belongsTo", Prod_routing::class, "prod_routing_id"],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'qaqc_insp_tmpl_id'],
        "getOwnerId" => ["belongsTo", User::class, "owner_id"],
    ];

    public function getSheets()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcInspChklsts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getOwnerId()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
