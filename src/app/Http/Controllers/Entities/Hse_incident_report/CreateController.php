<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Hse_incident_report;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'hse_incident_report';
    protected $data = Hse_incident_report::class;
    protected $action = "create";
}