<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_routing;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_routings',
        'edit' => 'read-prod_routings|create-prod_routings|edit-prod_routings|edit-others-prod_routings',
        'delete' => 'read-prod_routings|create-prod_routings|edit-prod_routings|edit-others-prod_routings|delete-prod_routings|delete-others-prod_routings'
    ];
}