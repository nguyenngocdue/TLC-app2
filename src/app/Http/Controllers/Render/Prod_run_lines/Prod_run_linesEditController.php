<?php

namespace App\Http\Controllers\Render\Prod_run_lines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_run_line;

class Prod_run_linesEditController extends CreateEditController
{
    protected $type = 'prod_run_line';
    protected $data = Prod_run_line::class;
    protected $action = "edit";
}
