<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_run;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}