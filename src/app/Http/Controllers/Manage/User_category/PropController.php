<?php

namespace App\Http\Controllers\Manage\User_category;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\User_category;

class PropController extends ManagePropController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
