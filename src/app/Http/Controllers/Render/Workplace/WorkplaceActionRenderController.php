<?php

namespace App\Http\Controllers\Render\Workplace;

use App\Http\Controllers\Render\ActionRenderController;
use App\Models\Workplace;

class WorkplaceActionRenderController extends ActionRenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
