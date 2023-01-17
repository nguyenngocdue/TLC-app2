<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_position1;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}