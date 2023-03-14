<?php

namespace App\Http\Controllers\Entities\Hse_incident_report;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Hse_incident_report;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'hse_incident_report';
    protected $typeModel = Hse_incident_report::class;
    protected $permissionMiddleware = [
        'read' => 'read-hse_incident_reports',
        'edit' => 'read-hse_incident_reports|create-hse_incident_reports|edit-hse_incident_reports|edit-others-hse_incident_reports',
        'delete' => 'read-hse_incident_reports|create-hse_incident_reports|edit-hse_incident_reports|edit-others-hse_incident_reports|delete-hse_incident_reports|delete-others-hse_incident_reports'
    ];
}