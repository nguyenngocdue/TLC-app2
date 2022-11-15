<?php

namespace App\Http\Controllers\Manage\User_type;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_type;

class ManageUser_typePropController extends ManagePropController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
