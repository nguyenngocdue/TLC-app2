<?php

namespace App\Http\Controllers\Entities\Prod_run_line;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_run_line;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_run_line';
    protected $typeModel = Prod_run_line::class;
}