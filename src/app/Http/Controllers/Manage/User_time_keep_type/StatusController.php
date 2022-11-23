<?php

namespace App\Http\Controllers\Manage\User_time_keep_type;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_time_keep_type;

class StatusController extends ManageStatusController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
}