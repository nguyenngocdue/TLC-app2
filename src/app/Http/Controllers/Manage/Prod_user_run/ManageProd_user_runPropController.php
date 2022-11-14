<?php

namespace App\Http\Controllers\Manage\Prod_user_run;

use App\Http\Controllers\Manage\ManagePropController;

class ManageProd_user_runPropController extends ManagePropController
{
    protected $type = 'prod_user_run';
    protected $typeModel = Prod_user_run::class;
}
