<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_routing_detail;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}
