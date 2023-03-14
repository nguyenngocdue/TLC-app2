<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_routing_detail;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}