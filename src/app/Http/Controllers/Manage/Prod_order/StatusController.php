<?php

namespace App\Http\Controllers\Manage\Prod_order;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_order;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}