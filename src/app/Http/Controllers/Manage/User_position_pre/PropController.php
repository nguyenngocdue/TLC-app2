<?php

namespace App\Http\Controllers\Manage\User_position_pre;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_position_pre;

class PropController extends ManagePropController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}
