<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Hse_incident_report;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'hse_incident_report';
    protected $typeModel = Hse_incident_report::class;
}
