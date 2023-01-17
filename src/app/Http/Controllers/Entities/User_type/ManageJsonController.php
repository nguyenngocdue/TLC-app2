<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_type;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}