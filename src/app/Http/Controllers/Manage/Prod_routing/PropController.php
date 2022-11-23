<?php

namespace App\Http\Controllers\Manage\Prod_routing;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_routing;

class PropController extends ManagePropController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
