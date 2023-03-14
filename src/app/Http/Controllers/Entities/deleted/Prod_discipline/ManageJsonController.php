<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_discipline;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}