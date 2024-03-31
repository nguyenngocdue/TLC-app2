<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_punchlist extends ModelExtended
{
    protected $fillable = [
        "name", "description", "qaqc_insp_chklst_id", "owner_id", "status",
    ];

    public static $eloquentParams = [
        "getLines" => ["hasMany", Qaqc_punchlist_line::class, 'qaqc_punchlist_id'],
        "getQaqcInspChklst" => ["belongsTo", Qaqc_insp_chklst::class, 'qaqc_insp_chklst_id'],
    ];
    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getQaqcInspChklst()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
