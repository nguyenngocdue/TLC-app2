<?php

namespace App\Http\Controllers\Manage\Prod_routing;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_routing;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}