<?php

namespace App\Http\Controllers\Manage\Prod_routing;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_routing;

class TablePropController extends ManageTablePropController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
