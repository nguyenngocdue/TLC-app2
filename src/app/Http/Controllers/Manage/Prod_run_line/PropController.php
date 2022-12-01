<?php

namespace App\Http\Controllers\Manage\Prod_run_line;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_run_line;

class PropController extends ManagePropController
{
    protected $type = 'prod_run_line';
    protected $typeModel = Prod_run_line::class;
}
