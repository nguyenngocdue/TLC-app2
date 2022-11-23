<?php

namespace App\Http\Controllers\Manage\Prod_discipline;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_discipline;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}