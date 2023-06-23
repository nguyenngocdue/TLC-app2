<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_insp_chklst_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "hse_insp_tmpl_sht_id", "owner_id",
        "progress", "status", "order_no", "assignee_1", "location_project", "start_time", "finish_time"
    ];
    protected $table = "hse_insp_chklst_shts";

    public $eloquentParams = [
        "getTmplSheet" => ["belongsTo", Hse_insp_tmpl_sht::class, 'hse_insp_tmpl_sht_id'],
        "getLines" => ["hasMany", Hse_insp_chklst_line::class, "hse_insp_chklst_sht_id"],
        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function getLines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function comment_rejected_reason()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getManyLineParams()
    {
        return [
            ["dataIndex" => 'order_no', 'invisible' => true, 'no_print' => true],
            ['dataIndex' => 'id',],
            // ['dataIndex' => 'description'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'hse_insp_tmpl_sht_id', 'rendererParam' => 'name'],
            ['dataIndex' => 'hse_insp_chklst_id'],
            ['dataIndex' => 'getMonitors1()', 'renderer' => 'agg_count'],
            ['dataIndex' => 'progress'],
            ['dataIndex' => 'status'],
        ];
    }
}
