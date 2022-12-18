<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_order;

class PropController extends AbstractPropController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
