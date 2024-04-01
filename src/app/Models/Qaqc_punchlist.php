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
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, 'qaqc_insp_chklst_id'],
        'signature_qaqc_punchlist' => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
    ];
    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        "signature_qaqc_punchlist_list()"  => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getChklst()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function signature_qaqc_punchlist()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function signature_qaqc_punchlist_list()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
