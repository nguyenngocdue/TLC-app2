<?php

namespace App\Http\Controllers\Manage\Prod_order;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_order;

class TablePropController extends ManageTablePropController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
