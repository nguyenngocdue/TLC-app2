<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\User_category;

class ManageController extends AbstractManageController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}