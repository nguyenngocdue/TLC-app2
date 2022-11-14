<?php

namespace App\Http\Controllers\Manage\User_position_pre;

use App\Http\Controllers\Manage\ManagePropController;

class ManageUser_position_prePropController extends ManagePropController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}
