<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_mir extends ModelExtended
{
    public static $hasDueDate = true;

    protected $fillable = [
        "id", "name", "doc_id", "description", "slug", "project_id", "sub_project_id", "status",
        "prod_discipline_id", "priority_id", "due_date", "assignee_1", "assignee_2", "owner_id", "closed_at"
    ];
    public static $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssignee1' => ["belongsTo", User::class, 'assignee_1'],
        'getAssignee2' => ["belongsTo", User::class, 'assignee_2'],

        "attachment_mir_po" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_delivery_docket" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_invoice" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_packing_list" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_material_test_report" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_material_test_certificate" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_conformity_certificate" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_quality_certificate" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_general_certificate" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_non_conformity_report" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_safety_data_sheet" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_technical_data_sheet" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_material_inspection_photo" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_mir_pdf_attached" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],

        "comment_asm_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_insp_rejected_reason" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_inspector_decision" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],

        "getNcrs" => ['morphMany', Qaqc_ncr::class, 'parent', 'parent_type', 'parent_id'],

        "getMonitors1" => ["belongsToMany", User::class, "ym2m_qaqc_mir_user_monitor_1"],
    ];

    public function comment_asm_rejected_reason()
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
    public function getDiscipline()
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
    public function attachment_mir_po()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_delivery_docket()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_invoice()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_packing_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_test_report()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_test_certificate()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_conformity_certificate()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_quality_certificate()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_general_certificate()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_non_conformity_report()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_safety_data_sheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_technical_data_sheet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_inspection_photo()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_pdf_attached()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getNcrs()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    }
}
