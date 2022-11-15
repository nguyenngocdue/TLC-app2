<?php

namespace App\Http\Controllers\Manage\User_position_pre;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_position_pre;

class ManageUser_position_preTablePropController extends ManageTablePropController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}
