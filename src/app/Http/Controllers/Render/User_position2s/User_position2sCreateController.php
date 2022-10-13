<?php

namespace App\Http\Controllers\Render\User_position2s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_position2;

class User_position2sCreateController extends CreateEditController
{
    protected $type = 'user_position2';
    protected $data = User_position2::class;
    protected $action = "create";
}
