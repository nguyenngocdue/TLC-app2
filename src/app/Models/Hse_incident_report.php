<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_incident_report extends ModelExtended
{
    protected $fillable = [
        'incident_doc_type_id', 'incident_doc_sub_type_id',
        'id', 'name', 'work_area_id', 'issue_datetime', 'injured_person', 'line_manager', 'owner',
        'number_injured_person', 'number_involved_person', 'issue_description',
        'accident_book_entry', 'time_in_hospital', 'time_out_hospital', 'investigation_finding',
        'lost_days'
    ];
    protected $table = "hse_incident_reports";
    public $nameless = true;

    public $eloquentParams = [
        "comment_by_clinic" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_by_line_manager" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_by_general_manager" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        'getInjuredPerson' => ["belongsTo", User::class, 'injured_person'],
        'getLineManager' => ["belongsTo", User::class, 'line_manager'],
        'getOwner' => ["belongsTo", User::class, 'owner'],
        'getWorkArea' => ['belongsTo', Work_area::class, 'work_area_id'],
        'getCorrectiveActions' => ['hasMany', Hse_corrective_action::class, 'hse_incident_report_id'],
        "attachment_1" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "attachment_2" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getDocType" => ['belongsTo', Term::class, 'incident_doc_type_id'],
        "getDocSubType" => ['belongsTo', Term::class, 'incident_doc_sub_type_id'],
    ];

    public $oracyParams = [
        "mainAffectedPart()" => ["getCheckedByField", Term::class],
        "natureOfInjury()" => ["getCheckedByField", Term::class],
        "treatmentInstruction()" => ["getCheckedByField", Term::class],
        "causeOfIssue()" => ["getCheckedByField", Term::class],
        "activityLeadToIssue()" => ["getCheckedByField", Term::class],
        "immediateCause()" => ["getCheckedByField", Term::class],
        "issueRootCause()" => ["getCheckedByField", Term::class],
    ];

    public function attachment_1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function attachment_2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_by_clinic()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_by_line_manager()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_by_general_manager()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getInjuredPerson()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getLineManager()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkArea()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCorrectiveActions()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDocType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getDocSubType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function mainAffectedPart()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function natureOfInjury()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function treatmentInstruction()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function causeOfIssue()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function activityLeadToIssue()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function immediateCause()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function issueRootCause()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
