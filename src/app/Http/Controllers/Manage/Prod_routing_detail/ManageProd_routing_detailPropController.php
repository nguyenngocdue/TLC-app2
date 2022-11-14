<?php

namespace App\Http\Controllers\Manage\Prod_routing_detail;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_routing_detail;

class ManageProd_routing_detailPropController extends ManagePropController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}
