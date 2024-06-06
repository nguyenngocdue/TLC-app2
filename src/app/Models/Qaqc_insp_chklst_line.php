<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_insp_chklst_line extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "control_type_id", "value", "value_on_hold", "order_no",
        "qaqc_insp_chklst_sht_id", "qaqc_insp_group_id",
        "qaqc_insp_control_value_id", "qaqc_insp_control_group_id", "owner_id", "inspector_id"
    ];
    public static $statusless = true;

    public static $eloquentParams = [
        "getGroup" => ["belongsTo", Qaqc_insp_group::class, "qaqc_insp_group_id"],
        "getSheet" => ["belongsTo", Qaqc_insp_chklst_sht::class, "qaqc_insp_chklst_sht_id"],
        "getControlGroup" => ["belongsTo", Qaqc_insp_control_group::class, "qaqc_insp_control_group_id"],
        "getControlValue" => ["belongsTo", Qaqc_insp_control_value::class, "qaqc_insp_control_value_id"],
        "getControlType" => ["belongsTo", Control_type::class, "control_type_id"],
        "getInspector" => ["belongsTo", User::class, "inspector_id"],

        // "getProject" => ["morphMany", Project::class, "complex"],
        // "getSubProject" => ["morphMany", Sub_Project::class, "complex"],
        // "getProdRouting" => ["morphMany", Prod_routing::class, "complex"],
        // "getProdOrder" => ["morphMany", Prod_order::class, "complex"],
        "getNcrs" => ['morphMany', Qaqc_ncr::class, 'parent', 'parent_type', 'parent_id'],
        "insp_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "insp_comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public function insp_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function insp_comments()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category')->orderBy('updated_at');
    }

    public function getGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getControlValue()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getNcrs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }

    public function getInspector()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProject()
    {
        $tmp = $this->getSheet->getChklst->getProdOrder->getSubProject;
        // $tmp = $this->getRun->getSheet->getChklst->getProdOrder->getSubProject;
        $relation = $tmp->belongsTo(Project::class, 'project_id');
        return $relation;
    }

    public function getSubProject()
    {
        $tmp = $this->getSheet->getChklst->getProdOrder;
        // $tmp = $this->getRun->getSheet->getChklst->getProdOrder;
        $relation = $tmp->belongsTo(Sub_project::class, 'sub_project_id');
        return $relation;
    }

    public function getProdRouting()
    {
        $tmp = $this->getSheet->getChklst->getProdOrder;
        // $tmp = $this->getRun->getSheet->getChklst->getProdOrder;
        $relation = $tmp->belongsTo(Prod_routing::class, 'prod_routing_id');
        return $relation;
    }

    public function getProdOrder()
    {
        $tmp = $this->getSheet->getChklst;
        // $tmp = $this->getRun->getSheet->getChklst;
        $relation = $tmp->belongsTo(Prod_order::class, 'prod_order_id');
        return $relation;
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id',],
            ['dataIndex' => 'qaqc_insp_chklst_sht_id', 'title' => 'Sheet ID', 'invisible' => !true, 'value_as_parent_id' => true],
            ['dataIndex' => 'qaqc_insp_group_id',],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'description'],
            ['dataIndex' => 'control_type_id',],
            ['dataIndex' => 'qaqc_insp_control_group_id',],
            ['dataIndex' => 'qaqc_insp_control_value_id',],
            ['dataIndex' => 'value'],
            ['dataIndex' => 'value_on_hold'],
        ];
    }
}
