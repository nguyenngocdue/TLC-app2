<?php

namespace App\Http\Controllers\Render\Prod_routings;

use App\Http\Controllers\Render\RenderController;
use App\Models\Prod_routing;

class Prod_routingsRenderController extends RenderController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_routings',
        'edit' => 'read-prod_routings|create-prod_routings|edit-prod_routings|edit-others-prod_routings',
        'delete' => 'read-prod_routings|create-prod_routings|edit-prod_routings|edit-others-prod_routings|delete-prod_routings|delete-others-prod_routings'
    ];
}