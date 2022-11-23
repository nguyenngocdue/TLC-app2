<?php

namespace App\Http\Controllers\Manage\Prod_run;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_run;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}