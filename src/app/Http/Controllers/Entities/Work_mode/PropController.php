<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Work_mode;

class PropController extends AbstractPropController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
