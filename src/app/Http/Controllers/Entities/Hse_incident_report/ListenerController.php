<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Hse_incident_report;

class ListenerController extends AbstractListenerController
{
    protected $type = 'hse_incident_report';
    protected $typeModel = Hse_incident_report::class;
}
