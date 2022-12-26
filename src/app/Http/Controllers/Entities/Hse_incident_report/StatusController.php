<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Hse_incident_report;

class StatusController extends AbstractStatusController
{
    protected $type = 'hse_incident_report';
    protected $typeModel = Hse_incident_report::class;
}