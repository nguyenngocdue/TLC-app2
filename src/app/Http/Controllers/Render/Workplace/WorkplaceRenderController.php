<?php

namespace App\Http\Controllers\Render\Workplace;

use App\Http\Controllers\Render\RenderController;
use App\Models\Workplace;

class WorkplaceRenderController extends RenderController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
