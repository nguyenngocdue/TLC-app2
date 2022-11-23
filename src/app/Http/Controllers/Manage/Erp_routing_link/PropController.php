<?php

namespace App\Http\Controllers\Manage\Erp_routing_link;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Erp_routing_link;

class PropController extends ManagePropController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
}
