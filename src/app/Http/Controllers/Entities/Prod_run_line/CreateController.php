<?php

namespace App\Http\Controllers\Entities\Prod_run_line;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_run_line;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'prod_run_line';
    protected $data = Prod_run_line::class;
    protected $action = "create";
}