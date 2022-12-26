<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Hse_incident_report extends ModelExtended
{
    protected $fillable = [
        'id', 'issue_location', 'issue_datetime', 'injured_person', 'line_manager', 'report_person',
        'number_injured_person', 'number_involved_person', 'issue_description',
        'accident_book_entry', 'time_in_hospital', 'time_out_hospital', 'investigation_finding',
    ];
    protected $table = "hse_incident_reports";

    public $eloquentParams = [
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
}
