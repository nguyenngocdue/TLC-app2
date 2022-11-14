<?php

namespace App\Http\Controllers\Manage\Prod_order;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageProd_orderTablePropController extends ManageTablePropController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
