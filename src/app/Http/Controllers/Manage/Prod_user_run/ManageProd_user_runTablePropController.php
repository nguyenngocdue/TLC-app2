<?php

namespace App\Http\Controllers\Manage\Prod_user_run;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageProd_user_runTablePropController extends ManageTablePropController
{
    protected $type = 'prod_user_run';
    protected $typeModel = Prod_user_run::class;
}
