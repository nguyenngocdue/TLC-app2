<?php

namespace App\Http\Controllers\Render\Prod_user_runs;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_user_run;

class Prod_user_runsEditController extends CreateEditController
{
    protected $type = 'prod_user_run';
    protected $data = Prod_user_run::class;
    protected $action = "edit";
}
