<?php

namespace App\Http\Controllers\Manage\User_position1;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_position1;

class PropController extends ManagePropController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}
