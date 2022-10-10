<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\EditController;
use App\Models\Workplace;

class WorkplacesEditController extends EditController
{
    protected $type = "workplace";
    protected $data = Workplace::class;
}
