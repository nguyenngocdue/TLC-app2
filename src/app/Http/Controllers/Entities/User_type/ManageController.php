<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\User_type;

class ManageController extends AbstractManageController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}