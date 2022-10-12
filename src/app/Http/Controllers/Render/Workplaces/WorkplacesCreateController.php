<?php

namespace App\Http\Controllers\Render\Workplaces;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Workplace;

class WorkplacesCreateController extends CreateEditController
{
    protected $type = "workplace";
    protected $data = Workplace::class;
    protected $action = "create";
}
