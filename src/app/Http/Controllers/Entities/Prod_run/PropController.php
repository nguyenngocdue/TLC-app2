<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_run;

class PropController extends AbstractPropController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
