<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Prod_routing;

class ManageController extends AbstractManageController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}