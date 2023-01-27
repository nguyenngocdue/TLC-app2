<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_run;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}