<?php

namespace App\Http\Controllers\Render\Prod_disciplines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Prod_discipline;

class Prod_disciplinesEditController extends CreateEditController
{
    protected $type = 'prod_discipline';
    protected $data = Prod_discipline::class;
    protected $action = "edit";
}
