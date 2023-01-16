<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Prod_run;

class ManageController extends AbstractManageController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}