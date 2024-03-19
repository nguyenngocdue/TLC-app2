<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_ncr extends ModelExtended
{
    public static $hasDueDate = true;

    protected $fillable = [
        "id", "name", "doc_id", "description", "slug", "project_id", "sub_project_id", "status",
        "parent_id", "parent_type", "prod_routing_id", "prod_order_id", "prod_discipline_1_id",
        "prod_discipline_id", "prod_discipline_2_id", "user_team_id", "priority_id", "due_date",
        "assignee_1", "assignee_2", "cause_analysis", "owner_id", "inter_subcon_id", "defect_root_cause_id",
        "defect_disposition_id", "closed_at", "severity", "report_type",
        "qty_man_power", "hour_per_man", "total_hour", "defect_severity", "defect_report_type"
    ];

    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getProdRouting" => ['belongsTo', Prod_routing::class, "prod_routing_id"],
        "getProdOrder" => ['belongsTo', Prod_order::class, "prod_order_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getDiscipline1" => ['belongsTo', Prod_discipline_1::class, "prod_discipline_1_id"],
        "getDiscipline2" => ['belongsTo', Prod_discipline_2::class, "prod_discipline_2_id"],
        "getUserTeam" => ['belongsTo', User_team_ncr::class, "user_team_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssignee1' => ["belongsTo", User::class, 'assignee_1'],
        'getAssignee2' => ["belongsTo", User::class, 'assignee_2'],
        "getInterSubcon" => ["belongsTo", Term::class, 'inter_subcon_id'],
        "getDefectRootCause" => ["belongsTo", Term::class, 'defect_root_cause_id'],
        "getDefectDisposition" => ["belongsTo", Term::class, 'defect_disposition_id'],
        "getSeverity" => ["belongsTo", Term::class, 'defect_severity'],
        "getReportType" => ["belongsTo", Term::class, 'defect_report_type'],

        'getQaqcCars' => ['hasMany', Qaqc_car::class, 'qaqc_ncr_id'],

        "getParent" => ['morphTo', Qaqc_ncr::class, 'parent_type', 'parent_id'],

        "attachment_defect_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_defect_pdfs" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_corrective_photos" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_corrective_pdfs" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "comment_asm_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_insp_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_inspector_decision" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_resolved" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function comment_asm_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_rejected_reason()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_insp_rejected_reason()
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
    public function comment_resolved()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getParent()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getProject()
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

    public function getDiscipline1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDiscipline2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getUserTeam()
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

    public function getAssignee2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getInterSubcon()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDefectRootCause()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDefectDisposition()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getQaqcCars()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSeverity()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getReportType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function attachment_defect_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_defect_pdfs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_corrective_photos()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_corrective_pdfs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getManyLineParams()
    {
        return [
            // ["dataIndex" => 'order_no', 'invisible' => true],
            ["dataIndex" => 'id'],
            ['dataIndex' => 'parent_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'parent_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'project_id', 'invisible' => true, 'value_as_project_id' => true],
            ['dataIndex' => 'sub_project_id', 'invisible' => true, 'value_as_sub_project_id' => true],
            ['dataIndex' => 'name',],
            ['dataIndex' => 'status', "footer" => "agg_none"],
            ['dataIndex' => 'assignee_1', 'title' => "Responsible Person"],

        ];
    }
}
