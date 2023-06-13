<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "qaqc_insp_chklst_id", "qaqc_insp_tmpl_sht_id", "owner_id",
        'progress', 'status', 'order_no'
    ];
    protected $table = "qaqc_insp_chklst_shts";

    public $eloquentParams = [
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, 'qaqc_insp_chklst_id'],
        "getTmplSheet" => ["belongsTo", Qaqc_insp_tmpl_sht::class, 'qaqc_insp_tmpl_sht_id'],

        "getLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_sht_id"],
        "getShtSigs" => ["hasMany", Qaqc_insp_chklst_sht_sig::class, "qaqc_insp_chklst_sht_id"],

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
    public function getShtSigs()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getChklst()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $tmp = $this->getChklst->getProdOrder->getSubProject;
        $relation = $tmp->belongsTo(Project::class, 'project_id');
        return $relation;
    }

    public function getSubProject()
    {
        $tmp = $this->getChklst->getProdOrder;
        $relation = $tmp->belongsTo(Sub_project::class, 'sub_project_id');
        return $relation;
    }
    public function getProdOrder()
    {
        $tmp = $this->getChklst;
        $relation = $tmp->belongsTo(Prod_order::class, 'prod_order_id');
        return $relation;
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
            ['dataIndex' => 'qaqc_insp_tmpl_sht_id', 'rendererParam' => 'name'],
            ['dataIndex' => 'qaqc_insp_chklst_id'],
            ['dataIndex' => 'getMonitors1()', 'renderer' => 'agg_count'],
            ['dataIndex' => 'progress'],
            ['dataIndex' => 'status'],
        ];
    }
}
