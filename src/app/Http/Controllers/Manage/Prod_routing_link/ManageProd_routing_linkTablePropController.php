<?php

namespace App\Http\Controllers\Manage\Prod_routing_link;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_routing_link;

class ManageProd_routing_linkTablePropController extends ManageTablePropController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}
