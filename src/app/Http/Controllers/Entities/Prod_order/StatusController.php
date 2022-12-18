<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_order;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}