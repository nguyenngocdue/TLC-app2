<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_routing_detail;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'prod_routing_detail';
    protected $data = Prod_routing_detail::class;
    protected $action = "create";
}