<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_wir extends ModelExtended
{
    public static $hasDueDate = true;

    protected $fillable = [
        "id", "name", "doc_id", "description", "slug", "project_id", "sub_project_id", "prod_routing_id", "status",
        "prod_discipline_id", "pj_level_id", "pj_module_type_id", "prod_order_id", "priority_id", "due_date",
        "assignee_1", "wir_description_id", "owner_id", "closed_at",
        "ncr_status_unique_value", "ncr_all_closed",
    ];
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $prodOrder = $this->getProdOrder;
        $wirDesc = $this->getWirDescription;
        return "[" . ($prodOrder->name ?? "") . "] - " . ($wirDesc->name ?? "");
    }

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getProdRouting" => ['belongsTo', Prod_routing::class, "prod_routing_id"],
        "getProdOrder" => ['belongsTo', Prod_order::class, "prod_order_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getWirDescription" => ['belongsTo', Wir_description::class, "wir_description_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssignee1' => ["belongsTo", User::class, 'assignee_1'],
        "getLines" => ["hasMany", Qaqc_wir_line::class, "qaqc_wir_id"],
        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_inspector_decision" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "getNcrs" => ['morphMany', Qaqc_ncr::class, 'parent', 'parent_type', 'parent_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_inspector_decision()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProject()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdRouting()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getProdOrder()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getWirDescription()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPriority()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getNcrs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
