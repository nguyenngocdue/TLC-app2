<?php

namespace App\Http\Controllers\Render\Prod_routing_links;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_routing_link;

class Prod_routing_linksCreateController extends CreateEditController
{
    protected $type = 'prod_routing_link';
    protected $data = Prod_routing_link::class;
    protected $action = "create";
}