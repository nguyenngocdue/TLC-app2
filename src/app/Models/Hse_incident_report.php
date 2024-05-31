<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_incident_report extends ModelExtended
{
    protected $fillable = [
        'incident_doc_type_id', 'incident_doc_sub_type_id',
        'id', 'name', 'work_area_id', 'issue_datetime',
        'injured_person',  'injured_staff_id', 'assignee_1', 'assignee_2', 'assignee_3', 'owner_id',
        'number_injured_person', 'number_involved_person', 'issue_description',
        'accident_book_entry', 'time_in_hospital', 'time_out_hospital', 'investigation_finding',
        'lost_days', 'status', 'injured_staff_position', 'manager_staff_id', 'manager_staff_position',
        'owner_staff_id', 'owner_staff_position',
        'first_date', 'employed_duration_in_year', 'injured_staff_cat', 'injured_staff_cat_desc',
        'injured_staff_discipline',  'need_to_transfer_position',
        'lost_value', 'lost_unit_id',
    ];
    // public static $nameless = true;

    public static $eloquentParams = [
        'getInjuredPerson' => ["belongsTo", User::class, 'injured_person'],
        'getLineManager' => ["belongsTo", User::class, 'assignee_1'],
        'getFactoryManager' => ["belongsTo", User::class, 'assignee_2'],
        'getHseManager' => ["belongsTo", User::class, 'assignee_3'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        "getDocType" => ['belongsTo', Term::class, 'incident_doc_type_id'],
        "getDocSubType" => ['belongsTo', Term::class, 'incident_doc_sub_type_id'],
        "getInjuredStaffCat" => ['belongsTo', User_category::class, 'injured_staff_cat'],
        "getInjuredStaffDiscipline" => ['belongsTo', User_discipline::class, 'injured_staff_discipline'],
        "getLostUnit" => ['belongsTo', Term::class, 'lost_unit_id'],

        "getCorrectiveActions" => ['morphMany', Hse_corrective_action::class, 'correctable', 'correctable_type', 'correctable_id'],

        "comment_by_clinic" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_by_line_manager" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_by_general_manager" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "attachment_hse_incident_doc" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_hse_witness" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_hse_hr_decision" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
    ];

    public static $oracyParams = [
        "mainAffectedPart()" => ["getCheckedByField", Term::class],
        "natureOfInjury()" => ["getCheckedByField", Term::class],
        "treatmentInstruction()" => ["getCheckedByField", Term::class],
        "causeOfIssue()" => ["getCheckedByField", Term::class],
        "activityLeadToIssue()" => ["getCheckedByField", Term::class],
        "immediateCause()" => ["getCheckedByField", Term::class],
        "issueRootCause()" => ["getCheckedByField", Term::class],
        "getMonitors1()" => ["getCheckedByField", User::class],
    ];

    public function attachment_hse_incident_doc()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_hse_witness()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_hse_hr_decision()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_by_clinic()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getLostUnit()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function comment_by_line_manager()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_by_general_manager()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getInjuredPerson()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getInjuredStaffCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getInjuredStaffDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLineManager()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getFactoryManager()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getHseManager()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCorrectiveActions()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDocType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDocSubType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function mainAffectedPart()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function natureOfInjury()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function treatmentInstruction()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function causeOfIssue()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function activityLeadToIssue()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function immediateCause()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function issueRootCause()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
