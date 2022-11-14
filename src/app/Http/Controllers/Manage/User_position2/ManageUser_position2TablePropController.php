<?php

namespace App\Http\Controllers\Manage\User_position2;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_position2;

class ManageUser_position2TablePropController extends ManageTablePropController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}
