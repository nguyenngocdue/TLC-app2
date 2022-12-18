<?php

namespace App\Http\Controllers\Entities\Erp_routing_link;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Erp_routing_link;

class StatusController extends AbstractStatusController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
}