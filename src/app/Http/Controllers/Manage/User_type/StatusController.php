<?php

namespace App\Http\Controllers\Manage\User_type;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_type;

class StatusController extends ManageStatusController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}