<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_routing;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}