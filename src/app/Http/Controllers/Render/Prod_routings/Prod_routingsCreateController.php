<?php

namespace App\Http\Controllers\Render\Prod_routings;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_routing;

class Prod_routingsCreateController extends CreateEditController
{
    protected $type = 'prod_routing';
    protected $data = Prod_routing::class;
    protected $action = "create";
}