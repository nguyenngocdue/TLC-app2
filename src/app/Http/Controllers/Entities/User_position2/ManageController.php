<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\User_position2;

class ManageController extends AbstractManageController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}