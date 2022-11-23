<?php

namespace App\Http\Controllers\Render\Erp_routing_links;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Erp_routing_link;

class Erp_routing_linksViewAllController extends ViewAllController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
    protected $permissionMiddleware = [
        'read' => 'read-erp_routing_links',
        'edit' => 'read-erp_routing_links|create-erp_routing_links|edit-erp_routing_links|edit-others-erp_routing_links',
        'delete' => 'read-erp_routing_links|create-erp_routing_links|edit-erp_routing_links|edit-others-erp_routing_links|delete-erp_routing_links|delete-others-erp_routing_links'
    ];
}