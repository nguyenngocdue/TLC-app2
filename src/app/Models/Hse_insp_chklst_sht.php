<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_chklst_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "hse_insp_tmpl_sht_id", "owner_id",
        "progress", "status", "order_no", "assignee_1", "workplace_id",
        "start_date",
        // "start_time", "finish_time"
    ];

    public static $eloquentParams = [
        "getTmplSheet" => ["belongsTo", Hse_insp_tmpl_sht::class, 'hse_insp_tmpl_sht_id'],
        "getLines" => ["hasMany", Hse_insp_chklst_line::class, "hse_insp_chklst_sht_id"],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getWorkplace' => ['belongsTo', Workplace::class, 'workplace_id'],
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
    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
