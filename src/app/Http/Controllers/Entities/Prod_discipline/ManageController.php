<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Prod_discipline;

class ManageController extends AbstractManageController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}