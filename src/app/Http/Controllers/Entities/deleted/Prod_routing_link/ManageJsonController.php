<?php

namespace App\Http\Controllers\Entities\Prod_routing_link;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_routing_link;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}