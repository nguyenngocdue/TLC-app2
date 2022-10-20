<?php

namespace App\Http\Controllers\Render\Prod_runs;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_run;

class Prod_runsEditController extends CreateEditController
{
    protected $type = 'prod_run';
    protected $data = Prod_run::class;
}