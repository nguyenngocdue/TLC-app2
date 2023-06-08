<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_tmpl_sht extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "qaqc_insp_tmpl_id", "owner_id", 'order_no'];
    protected $table = "qaqc_insp_tmpl_shts";
    protected static $statusless = true;

    public $eloquentParams = [
        "getTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, 'qaqc_insp_tmpl_id'],

        "getLines" => ["hasMany", Qaqc_insp_tmpl_line::class, "qaqc_insp_tmpl_sht_id"],
    ];

    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTmpl()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id'],
            ['dataIndex' => 'qaqc_insp_tmpl_id'],
            ['dataIndex' => 'name'],
            // ['dataIndex' => 'description'],
            // ['dataIndex' => 'getLines', 'rendererParam' => 'description'],
        ];
    }
}
