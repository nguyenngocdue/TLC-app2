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

        "getDefExtInsp" => ["belongsToMany", User::class, "ym2m_qaqc_insp_tmpl_sht_user_def_ext_insp_1"],
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

    public function getDefExtInsp()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
        ];
    }
}
