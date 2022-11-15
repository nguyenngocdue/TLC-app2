<?php

namespace App\Http\Controllers\Manage\User_position3;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_position3;

class ManageUser_position3TablePropController extends ManageTablePropController
{
    protected $type = 'user_position3';
    protected $typeModel = User_position3::class;
}
