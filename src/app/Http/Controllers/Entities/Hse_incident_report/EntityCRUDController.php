<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Hse_incident_report;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'hse_incident_report';
    protected $data = Hse_incident_report::class;
}