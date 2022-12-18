<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\User_type;

class PropController extends AbstractPropController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
