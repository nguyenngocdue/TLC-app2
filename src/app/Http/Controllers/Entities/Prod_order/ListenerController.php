<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_order;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
