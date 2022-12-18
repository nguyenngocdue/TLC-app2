<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_routing_detail;

class PropController extends AbstractPropController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}
