<?php

namespace App\Http\Controllers\Manage\Prod_routing;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageProd_routingTablePropController extends ManageTablePropController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
