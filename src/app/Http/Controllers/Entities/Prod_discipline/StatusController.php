<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_discipline;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}