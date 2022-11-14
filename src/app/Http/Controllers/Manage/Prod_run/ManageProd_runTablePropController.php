<?php

namespace App\Http\Controllers\Manage\Prod_run;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageProd_runTablePropController extends ManageTablePropController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
