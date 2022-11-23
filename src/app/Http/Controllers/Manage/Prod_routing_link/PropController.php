<?php

namespace App\Http\Controllers\Manage\Prod_routing_link;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_routing_link;

class PropController extends ManagePropController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}
