<?php

namespace App\Http\Controllers\Manage\User_position3;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_position3;

class PropController extends ManagePropController
{
    protected $type = 'user_position3';
    protected $typeModel = User_position3::class;
}
