<?php

namespace App\Http\Controllers\Render\Workplace;

use App\Http\Controllers\Render\EditController;
use App\Models\Workplace;

class WorkplaceEditController extends EditController
{
   protected $type = "workplace";
    protected $data = Workplace::class;
}
