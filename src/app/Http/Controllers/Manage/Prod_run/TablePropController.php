<?php

namespace App\Http\Controllers\Manage\Prod_run;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_run;

class TablePropController extends ManageTablePropController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
