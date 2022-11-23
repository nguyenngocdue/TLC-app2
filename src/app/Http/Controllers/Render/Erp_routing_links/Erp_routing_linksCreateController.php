<?php

namespace App\Http\Controllers\Render\Erp_routing_links;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Erp_routing_link;

class Erp_routing_linksCreateController extends CreateEditController
{
    protected $type = 'erp_routing_link';
    protected $data = Erp_routing_link::class;
    protected $action = "create";
}