<?php

namespace App\Http\Controllers\Render\Prod_routing_links;

use App\Http\Controllers\Render\RenderController;
use App\Models\Prod_routing_link;

class Prod_routing_linksRenderController extends RenderController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_routing_links',
        'edit' => 'read-prod_routing_links|create-prod_routing_links|edit-prod_routing_links|edit-others-prod_routing_links',
        'delete' => 'read-prod_routing_links|create-prod_routing_links|edit-prod_routing_links|edit-others-prod_routing_links|delete-prod_routing_links|delete-others-prod_routing_links'
    ];
}