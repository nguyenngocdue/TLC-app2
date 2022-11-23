<?php

namespace App\Http\Controllers\Manage\Prod_routing_link;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_routing_link;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}