<?php

namespace App\Http\Controllers\Manage\Prod_run;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Prod_run;

class ManageProd_runPropController extends ManagePropController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
