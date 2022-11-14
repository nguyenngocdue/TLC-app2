<?php

namespace App\Http\Controllers\Manage\User_position1;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\User_position1;

class ManageUser_position1TablePropController extends ManageTablePropController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}
