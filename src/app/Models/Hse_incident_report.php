<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_incident_report extends ModelExtended
{
    public $menuTitle = "HSE Incident Reports";
    protected $fillable = [
        'id', 'issue_location', 'issue_datetime', 'injured_person', 'line_manager', 'report_person',
        'number_injured_person', 'number_involved_person', 'issue_description',
        'accident_book_entry', 'time_in_hospital', 'time_out_hospital', 'investigation_finding',
    ];
    protected $table = "hse_incident_reports";

    public $eloquentParams = [
        "comments" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        'getInjuredPerson' => ["belongsTo", User::class, 'injured_person'],
        'getLineManager' => ["belongsTo", user::class, 'line_manager'],
        'getReportPerson' => ["belongsTo", User::class, 'report_person'],
    ];

    public $oracyParams = [
        "mainAffectedPart()" => ["getCheckedByField", Term::class],
        "natureOfInjury()" => ["getCheckedByField", Term::class],
        "treatmentInstruction()" => ["getCheckedByField", Term::class],
        "accidentClassification()" => ["getCheckedByField", Term::class],
        "causeOfIssue()" => ["getCheckedByField", Term::class],
        "activityLeadToIssue()" => ["getCheckedByField", Term::class],
        "immediateCause()" => ["getCheckedByField", Term::class],
        "issueRootCause()" => ["getCheckedByField", Term::class],
    ];

    public function comments()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
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
    public function getReportPerson()
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
    public function accidentClassification()
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
