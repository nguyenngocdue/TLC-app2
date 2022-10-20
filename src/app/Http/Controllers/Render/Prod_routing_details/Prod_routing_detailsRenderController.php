<?php

namespace App\Http\Controllers\Render\Prod_routing_details;

use App\Http\Controllers\Render\RenderController;
use App\Models\Prod_routing_detail;

class Prod_routing_detailsRenderController extends RenderController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_routing_details',
        'edit' => 'read-prod_routing_details|create-prod_routing_details|edit-prod_routing_details|edit-others-prod_routing_details',
        'delete' => 'read-prod_routing_details|create-prod_routing_details|edit-prod_routing_details|edit-others-prod_routing_details|delete-prod_routing_details|delete-others-prod_routing_details'
    ];
}