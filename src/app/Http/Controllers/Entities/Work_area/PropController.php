<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Work_area;

class PropController extends AbstractPropController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}
