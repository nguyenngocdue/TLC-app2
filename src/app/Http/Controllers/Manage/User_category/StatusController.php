<?php

namespace App\Http\Controllers\Manage\User_category;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User_category;

class StatusController extends ManageStatusController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}