<?php

namespace App\Http\Controllers\Render\Prod_lines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_line;

class Prod_linesEditController extends CreateEditController
{
    protected $type = 'prod_line';
    protected $data = Prod_line::class;
}