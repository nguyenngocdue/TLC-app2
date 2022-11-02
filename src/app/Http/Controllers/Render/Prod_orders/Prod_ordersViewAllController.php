<?php

namespace App\Http\Controllers\Render\Prod_orders;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Prod_order;

class Prod_ordersViewAllController extends ViewAllController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
    protected $permissionMiddleware = [
        'read' => 'read-prod_orders',
        'edit' => 'read-prod_orders|create-prod_orders|edit-prod_orders|edit-others-prod_orders',
        'delete' => 'read-prod_orders|create-prod_orders|edit-prod_orders|edit-others-prod_orders|delete-prod_orders|delete-others-prod_orders'
    ];
}
