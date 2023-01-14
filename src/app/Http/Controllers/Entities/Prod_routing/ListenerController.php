<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_routing;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
