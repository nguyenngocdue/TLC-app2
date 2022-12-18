<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_run;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'prod_run';
    protected $data = Prod_run::class;
    protected $action = "create";
}