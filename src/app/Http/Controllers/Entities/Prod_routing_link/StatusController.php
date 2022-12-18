<?php

namespace App\Http\Controllers\Entities\Prod_routing_link;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_routing_link;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}