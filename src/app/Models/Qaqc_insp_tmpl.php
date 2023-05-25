<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl extends ModelExtended
{
    protected $fillable = ["id", "prod_routing_id", "name", "description", "slug", "owner_id"];
    protected $table = "qaqc_insp_tmpls";
    protected static $statusless = true;

    public $eloquentParams = [
        "getProdRouting" => ["belongsTo", Prod_routing::class, "prod_routing_id"],

        "getSheets" => ["hasMany", Qaqc_insp_tmpl_sht::class, "qaqc_insp_tmpl_id"],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'qaqc_insp_tmpl_id'],
    ];

    public $oracyParams = [
        "getProdRoutings()" => ["getCheckedByField", Prod_routing::class],
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

    public function getProdRoutings()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
