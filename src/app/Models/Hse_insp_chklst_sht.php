<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_chklst_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "hse_insp_tmpl_sht_id", "owner_id",
        "progress", "status", "order_no", "assignee_1", "work_area_id", "start_time", "finish_time"
    ];
    protected $table = "hse_insp_chklst_shts";

    public static $eloquentParams = [
        "getTmplSheet" => ["belongsTo", Hse_insp_tmpl_sht::class, 'hse_insp_tmpl_sht_id'],
        "getLines" => ["hasMany", Hse_insp_chklst_line::class, "hse_insp_chklst_sht_id"],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id',],
            // ['dataIndex' => 'description'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'hse_insp_tmpl_sht_id', 'rendererParam' => 'name'],
            ['dataIndex' => 'getMonitors1()', 'renderer' => 'agg_count'],
            ['dataIndex' => 'progress'],
            ['dataIndex' => 'status'],
        ];
    }
}
