<?php

namespace App\Http\Controllers\Manage\Prod_routing_detail;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_routing_detail;

class ManageProd_routing_detailTablePropController extends ManageTablePropController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}
