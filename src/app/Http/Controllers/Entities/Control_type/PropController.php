<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Control_type;

class PropController extends AbstractPropController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}
