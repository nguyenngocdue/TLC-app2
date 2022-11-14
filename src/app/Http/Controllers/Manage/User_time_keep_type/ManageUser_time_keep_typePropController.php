<?php

namespace App\Http\Controllers\Manage\User_time_keep_type;

use App\Http\Controllers\Manage\ManagePropController;

class ManageUser_time_keep_typePropController extends ManagePropController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
}
