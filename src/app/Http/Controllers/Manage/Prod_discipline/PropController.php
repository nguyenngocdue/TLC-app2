<?php

namespace App\Http\Controllers\Manage\Prod_discipline;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_discipline;

class PropController extends ManagePropController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
