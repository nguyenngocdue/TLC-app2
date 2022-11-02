<?php

namespace App\Http\Controllers\Render\Prod_routing_details;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_routing_detail;

class Prod_routing_detailsEditController extends CreateEditController
{
    protected $type = 'prod_routing_detail';
    protected $data = Prod_routing_detail::class;
    protected $action = "edit";
}
