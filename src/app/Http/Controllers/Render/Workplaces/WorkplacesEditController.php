<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Workplace;

class WorkplacesEditController extends CreateEditController
{
    protected $type = "workplace";
    protected $data = Workplace::class;
    protected $action = "edit";
}
