<?php

namespace App\Http\Controllers\Manage\User_position1;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_position1;

class StatusController extends ManageStatusController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}