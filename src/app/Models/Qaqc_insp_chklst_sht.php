<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_sht extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "slug", "owner_id",
        "qaqc_insp_chklst_id", "qaqc_insp_tmpl_sht_id", "prod_discipline_id",
        'progress', 'status', 'order_no',
        "assignee_1", "assignee_2",
    ];

    public static $eloquentParams = [
        "getChklst" => ["belongsTo", Qaqc_insp_chklst::class, 'qaqc_insp_chklst_id'],
        "getTmplSheet" => ["belongsTo", Qaqc_insp_tmpl_sht::class, 'qaqc_insp_tmpl_sht_id'],

        "getLines" => ["hasMany", Qaqc_insp_chklst_line::class, "qaqc_insp_chklst_sht_id"],
        'signature_qaqc_chklst_3rd_party' => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],

        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "getProdDiscipline" => ['belongsTo', Prod_discipline::class, 'prod_discipline_id'],

        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
        'getAssignee2' => ['belongsTo', User::class, 'assignee_2'],
        //Many to many
        "getMonitors1" => ["belongsToMany", User::class, "ym2m_qaqc_insp_chklst_sht_user_monitor_1"],
        "council_member_list" => ["belongsToMany", User::class, "ym2m_qaqc_insp_chklst_sht_user_council_member"],
        "signature_qaqc_chklst_3rd_party_list"  => ["belongsToMany", User::class, "ym2m_qaqc_insp_chklst_sht_user_3rd_party"],
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
    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function signature_qaqc_chklst_3rd_party()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    // public function getShtSigs()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    public function getChklst()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTmplSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
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
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function council_member_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function signature_qaqc_chklst_3rd_party_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
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
            // ['dataIndex' => 'description'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'qaqc_insp_tmpl_sht_id', 'rendererParam' => 'name'],
            ['dataIndex' => 'qaqc_insp_chklst_id'],
            ['dataIndex' => 'getMonitors1', 'renderer' => 'agg_count'],
            ['dataIndex' => 'progress'],
            ['dataIndex' => 'status'],
        ];
    }
}
