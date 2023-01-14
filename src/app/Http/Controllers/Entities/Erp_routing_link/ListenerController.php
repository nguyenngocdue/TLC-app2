<?php

namespace App\Http\Controllers\Entities\Erp_routing_link;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Erp_routing_link;

class ListenerController extends AbstractListenerController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
}
