<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Hse_incident_report;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'hse_incident_report';
    protected $typeModel = Hse_incident_report::class;
}