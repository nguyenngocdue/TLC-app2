<?php

namespace App\Http\Controllers\Manage\Prod_order;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_order;

class ManageProd_orderPropController extends ManagePropController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
