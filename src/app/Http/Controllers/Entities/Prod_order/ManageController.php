<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Prod_order;

class ManageController extends AbstractManageController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}