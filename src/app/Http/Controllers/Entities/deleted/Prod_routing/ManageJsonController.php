<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_routing;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}