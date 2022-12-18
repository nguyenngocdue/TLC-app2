<?php

namespace App\Http\Controllers\Entities\Prod_run_line;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_run_line;

class PropController extends AbstractPropController
{
    protected $type = 'prod_run_line';
    protected $typeModel = Prod_run_line::class;
}
