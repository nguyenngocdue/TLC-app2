<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_category;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}