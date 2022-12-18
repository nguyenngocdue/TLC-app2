<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_routing;

class PropController extends AbstractPropController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
