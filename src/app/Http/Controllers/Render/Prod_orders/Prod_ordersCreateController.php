<?php

namespace App\Http\Controllers\Render\Prod_orders;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_order;

class Prod_ordersCreateController extends CreateEditController
{
    protected $type = 'prod_order';
    protected $data = Prod_order::class;
    protected $action = "create";
}