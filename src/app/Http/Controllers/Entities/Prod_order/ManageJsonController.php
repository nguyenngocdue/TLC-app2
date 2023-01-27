<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_order;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}