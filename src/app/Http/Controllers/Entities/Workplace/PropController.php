<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Workplace;

class PropController extends AbstractPropController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
