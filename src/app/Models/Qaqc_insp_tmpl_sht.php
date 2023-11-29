<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use Illuminate\Support\Str;

class Qaqc_insp_tmpl_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "owner_id", 'order_no',
        "qaqc_insp_tmpl_id", "prod_discipline_id",
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getTmpl" => ["belongsTo", Qaqc_insp_tmpl::class, 'qaqc_insp_tmpl_id'],
        "getLines" => ["hasMany", Qaqc_insp_tmpl_line::class, "qaqc_insp_tmpl_sht_id"],
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getProdDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id',],
            ['dataIndex' => 'qaqc_insp_tmpl_id', 'value_as_parent_id' => true],
            ['dataIndex' => 'name'],
            // ['dataIndex' => 'description'],
            // ['dataIndex' => 'getLines',],
            // ['dataIndex' => 'getMonitors1()', 'renderer' => 'agg_count'],
        ];
    }
}
