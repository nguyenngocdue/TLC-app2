<?php

namespace App\Http\Controllers\Entities\Erp_routing_link;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Erp_routing_link;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'erp_routing_link';
    protected $data = Erp_routing_link::class;
    protected $action = "create";
}