<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_routing_detail;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_routing_details',
        'edit' => 'read-prod_routing_details|create-prod_routing_details|edit-prod_routing_details|edit-others-prod_routing_details',
        'delete' => 'read-prod_routing_details|create-prod_routing_details|edit-prod_routing_details|edit-others-prod_routing_details|delete-prod_routing_details|delete-others-prod_routing_details'
    ];
}