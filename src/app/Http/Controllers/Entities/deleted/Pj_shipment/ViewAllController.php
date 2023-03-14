<?php

namespace App\Http\Controllers\Entities\Pj_shipment;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Pj_shipment;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'pj_shipment';
    protected $typeModel = Pj_shipment::class;
    protected $permissionMiddleware = [
        'read' => 'read-pj_shipments',
        'edit' => 'read-pj_shipments|create-pj_shipments|edit-pj_shipments|edit-others-pj_shipments',
        'delete' => 'read-pj_shipments|create-pj_shipments|edit-pj_shipments|edit-others-pj_shipments|delete-pj_shipments|delete-others-pj_shipments'
    ];
}