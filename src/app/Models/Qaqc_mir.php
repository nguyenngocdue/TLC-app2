<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Qaqc_mir extends ModelExtended
{
    protected $fillable = ["id", "name", "description", "slug", "project_id", "sub_project_id",
     "prod_discipline_id", "priority_id", "due_date", "assignee"];
    protected $table = "qaqc_mirs";

    public $eloquentParams = [
        "getProject" => ['belongsTo', Project::class, "project_id"],
        "getSubProject" => ['belongsTo', Sub_project::class, "sub_project_id"],
        "getDiscipline" => ['belongsTo', Prod_discipline::class, "prod_discipline_id"],
        "getPriority" => ['belongsTo', Priority::class, "priority_id"],
        'getAssigneeTo' => ["belongsTo", User::class, 'assignee_to'],
        'getInspector' => ["belongsTo", User::class, 'inspected_by'],
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
    ];

    public function getProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getSubProject()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPriority()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssigneeTo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getInspector()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function attachment_mir_po()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_delivery_docket()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_invoice()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_packing_list()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_test_report()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_test_certificate()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_conformity_certificate()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_quality_certificate()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_general_certificate()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_non_conformity_report()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_safety_data_sheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_technical_data_sheet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_material_inspection_photo()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_mir_pdf_attached()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
}
