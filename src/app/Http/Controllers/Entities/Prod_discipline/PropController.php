<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_discipline;

class PropController extends AbstractPropController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
