<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "owner_id"];
    protected $table = "qaqc_insp_tmpls";
    protected static $statusless = true;

    public static $eloquentParams = [
        "getSheets" => ["hasMany", Qaqc_insp_tmpl_sht::class, "qaqc_insp_tmpl_id"],
        "getQaqcInspChklsts" => ['hasMany', Qaqc_insp_chklst::class, 'qaqc_insp_tmpl_id'],
    ];

    public static $oracyParams = [
        "getProdRoutingsOfInspTmpl()" => ["getCheckedByField", Prod_routing::class],
        "getExternalInspectorsOfQaqcInspTmpl()" => ['getCheckedByField', User::class],
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
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getExternalInspectorsOfQaqcInspTmpl()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
