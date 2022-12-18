<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Prod_order;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_orders',
        'edit' => 'read-prod_orders|create-prod_orders|edit-prod_orders|edit-others-prod_orders',
        'delete' => 'read-prod_orders|create-prod_orders|edit-prod_orders|edit-others-prod_orders|delete-prod_orders|delete-others-prod_orders'
    ];
}