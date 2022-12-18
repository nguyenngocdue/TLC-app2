<?php

namespace App\Http\Controllers\Entities\Prod_routing_link;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_routing_link;

class PropController extends AbstractPropController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}
