<?php

namespace App\Http\Controllers\Manage\User_position3;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_position3;

class StatusController extends ManageStatusController
{
    protected $type = 'user_position3';
    protected $typeModel = User_position3::class;
}