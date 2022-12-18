<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\User_discipline;

class PropController extends AbstractPropController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
